// server.js (fixed version for cPanel with correct BASE_PATH handling)
const express = require('express');
const cors = require('cors');
const { GoogleGenerativeAI } = require('@google/generative-ai');

const app = express();
const PORT = process.env.PORT || 3000;

// Ensure BASE_PATH starts and ends correctly
let BASE_PATH = process.env.PASSENGER_BASE_URI || '/';
if (!BASE_PATH.startsWith('/')) BASE_PATH = '/' + BASE_PATH;
if (!BASE_PATH.endsWith('/')) BASE_PATH = BASE_PATH + '/';

// CORS: Only allow requests from your frontend
app.use(cors({
    origin: 'https://chatrockai.digital', // frontend domain
    methods: ['GET', 'POST']
}));

app.use(express.json());

// API Keys
const API_KEYS = [
    process.env.GEMINI_API_KEY_1,
    process.env.GEMINI_API_KEY_2,
    process.env.GEMINI_API_KEY_3
].filter(Boolean);

if (API_KEYS.length === 0) {
    console.error("Error: No GEMINI_API_KEYs are set in cPanel Environment Variables.");
    process.exit(1);
}

// Knowledge Base
const knowledgeBase = {
    "Business Name": "ChatRock AI",

    "Business Owner": "Saurav Dhakal from Birtamode, Jhapa",

    "Business contact no.": "Phone number= 9702841056 or 9768808974. You can also mail on chatrock.ai@gmail.com",

    "What does the business do": "We help businesses create smart, interactive chatbots that can be easily integrated into their websites. Our chatbots are designed to answer customer queries instantly, improve user experience, and save valuable time for your business.",

    "What does our chatbot can  do": "With a chatbot on your website, you can -Provide 24/7 customer support, Reduce response time and improve customer satisfaction, -Handle common queries automatically, -Clear the customers doubts, -Free up your team to focus on what matters most",
    "": "Whether you run a small business or a growing company, our chatbot solutions make your website more engaging, efficient, and customer-friendly.",

    "How will the chatbot be integrated into their website": "Once the project is over, we will send you the project file or folder which contains the all the frontend and backend. You can easily integrate the chatbot on your website by adding the chatbot files in your website folder. You don't need to make any other changes with the website.",

    "Can I make changes in the chatbot": "Yes, Once you get the project file/folder, you will get the full control over the chatbot. You can make any changes as per your wish. You can add or edit the knowledge-base for more details and accurate information.",

    "What is knowledge-base": "Knowledge-base is the place where you can add your business details so that AI can answer the queries of your customers.",

    "Extra information about knowledge-base": "Here is how AI will work. When your customers ask a query, AI will look for the related details in the knowledge-base. If it finds any related details, it will answer according to the details in the knowledge-base. If it don't find any related information, it will be unable to answer the queries.",

    "What are the details I should add in the knowledge-base": "You must add all the details that you think your customers will ask. It is best to add all the informations you can share in public for best response.",

    "Should the asked queries be same as it is in the knowledg-base": " No, the questions is not required to be same which is in the knowledge-base. AI will just look for the similar details in the knowlegde base and if it finds any similar details, it will response to the user, matching the answer with the question.",

    "How can I add or remove the information in the knowledge-base": "You can easily add new information or remove older information form the knowledge-base. You have to look for file named as (server.js) in the project folder where you can find the details that are on your knowledge-base. You can easily add or remove the details form there and save it.",

    "AI used in the chatbot": "We have used Gemini 1.5 flash API for the chatbot, which is free and provides fast response need for the customer support.",

    "What is API key": "API key is a unique code that connects your chatbot to the service or platform it needs to run. Without the API key, the chatbot won’t know how to access the service securely.",

    "Do you need API keys to run the chatbot": "Yes, API keys are the most important part of the chatbot. An API key is like an access card. Just like you need a card to enter a secure building, the chatbot needs an API key to talk to the AI service and fetch answers. It makes sure only your chatbot can use the service, and no one else.",

    "Limitations of API key": "There are some limitations of this API key as well. The key has limit to process only 50 requests a day (50 messages per day). Which means it can only reply to 50 messages a day. But we have a solution for this. Using multiple API keys will increase your quotas per day. for example: 1 key= 50 messages per day, 2 keys= 100 messages per day.",

    "Do you need to get your API keys yourself": "API keys are like the password to connect to the server, so it is safer option to get your API keys by yourself.",

    "How to get an API key": "Once the project is over, we will send you the project folder which also contains step by step guide on how to get the API keys and how to connects it to the system.",

    "What is the cost of the chatbot": "For now the exact cost you need to pay chatRock AI is Rs. 38,200. It is not monthly subscription or anything like that, instead it is a one time payment.",

    "Why does it cost high": "It is not monthly subscription or anything like that, instead it is just a one time payment.",

    "Do i need to pay for API keys": "No, you don't need to pay for API keys. We have used Google's Gemini 1.5 flash model, which allows you to use API for free.",

    "How can I make the payment for the chatbot to chatRock AI" : "We will send you our payment details with the project folder once the project is over. You can pay once you integrate the chatbot in your website and test it.",

    "How long will it take to complete the project": "It usually take 2-3 days for the project to complete and test it. The project includes both frontend and backend, you will just need to add the API keys and integrate it on your website.",

    "What if the chatbot does not work": "In this case, you can easily contact the team and they will guide you to make it work.",

    "Have you worked with other business before this": "yes, we have worked with several other businesses before which I cannot disclose.",

    "Can the chatbot collect customers email and contact no.": "No, it cannot collect customers details.",

    "Will it work on mobile or desktop": "It will work on both mobile and desktop without any problems.",

    "Can the chatbot only reply with text": "Yes, the chatbot can only reply with the text. It cannot use images, videos or voice messages.",

    "Does the AI learns or improve over time": "No, the AI will not learns and improve over time. The quality of the answers depends upon the quality of details you add in the knowledge-base.",

    "How can I get a chatbot": "To get a chatbot for your business, please click the (Get the chatbot) button on the homepage. Fill up the form and submit it. The chatbot will be ready within 2-3 days. We will send you the chatbot project folder (including payment details) via email that you will be submitted in the form.",

    "Form details": "Project Name= The name of your project. You can give any name to your project, Business Name= The registered Name of your business or organization, Business contact number= The contact number of your business, Business official website= The URL of your business official website, Your Name= Name of the individual filling up the form, Your contact number= Contact number of the individual who is filling the form, E-mail= Business email where we can send the project when ready to be send, Expected messages= Number of messages you expect to receive daily. It is needed to estimate the number of API keys you will need for your chatbot, Business details= The details you want to be added in the knowledge-base. Note- AI can only access the information you add on your knowledge-base.",

    "Maximum Messages": "While filling the form you will get the option to choose number of messages you expect to receive but the maximum number of expected messages is set as 200. You can go beyond the maximum limit of you expect to get more than 200 messages per day. You can easily make changes on the backend file to add more API keys. You will get further details about API keys while receiving the chatbot project.",

    "Form is not submitted": "There may be any problem with the server or network error. Please try again later or contact us directly.",

    "Note": "For the queries like how to integrate the chatbot, how to get API keys, how to connect API keys and how to make payments, we will send you mail once the project is ready.",
 
    "Lifetime support": "We usually don't provide the lifetime support but we will help up until the chatbot is integrated into your website",
    
    "Does your chatbot support multiple languages": "Yes, it can chat with your customers in any languages.",
};

const systemPrompt = `You are a helpful customer service chatbot for "ChatRock AI". Your main goal is to answer customer queries using the provided knowledge base. 
Use the information in the knowledge base. If someone asks questions not related to ChatRock AI, respond politely that you cannot help.
Do not highlight any text while responding to the queries and never use ( ** ) to highlight the text. Do not give the informations like "Business owner" until they ask for.

Knowledge base: ${JSON.stringify(knowledgeBase, null, 2)}`;

// Initialize Models
const models = API_KEYS.map(key => {
    const genAI = new GoogleGenerativeAI(key);
    return genAI.getGenerativeModel({
        model: "gemini-1.5-flash",
        systemInstruction: {
            role: "system",
            parts: [{ text: systemPrompt }]
        }
    });
});

// Helper: Send message with fallback
async function sendWithFallback(prompt, history) {
    let lastError = null;
    for (let i = 0; i < models.length; i++) {
        try {
            const chat = models[i].startChat({ history });
            const result = await chat.sendMessage(prompt);
            return result.response.text();
        } catch (error) {
            console.error('Error caught in fallback:', error);
            const status = error.response?.status || error.status || error.statusCode;
            if (status === 429) {
                lastError = error;
                console.warn(`API key ${i + 1} quota exceeded, switching to next key.`);
                continue;
            } else {
                throw error;
            }
        }
    }
    throw lastError || new Error('All API keys failed.');
}

// Test GET route
app.get(BASE_PATH, (req, res) => {
    res.send(`Backend is running! Use POST ${BASE_PATH}chat to interact with the chatbot.`);
});

// Chat endpoint
app.post(BASE_PATH + 'chat', async (req, res) => {
    try {
        const { prompt, history } = req.body;
        if (!prompt) return res.status(400).send({ error: 'Prompt is required.' });

        const textResponse = await sendWithFallback(prompt, history || []);
        res.json({ response: textResponse });

    } catch (error) {
        console.error('Error in chat endpoint:', error);
        const status = error.response?.status || error.status || error.statusCode;
        if (status === 429) {
            res.status(429).json({ error: "Sorry, all API keys have exceeded their quota." });
        } else {
            res.status(500).json({ error: "Sorry, I'm having trouble." });
        }
    }
});

// Start server
app.listen(PORT, '0.0.0.0', () => {
    console.log(`Server is running at port ${PORT}, Base Path: ${BASE_PATH}`);
});