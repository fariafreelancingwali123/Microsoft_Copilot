<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <!-- Adding the CSS directly in the <head> -->
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            color: #292827;
            line-height: 1.6;
        }

        /* Header styles */
        header {
            background-color: #fff;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #292827;
        }

        /* Container styles */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Search bar styles */
        .search-bar {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
        }

        .search-bar input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #0078d4;
            box-shadow: 0 0 0 2px rgba(0, 120, 212, 0.2);
        }

        .search-bar button {
            background-color: #0078d4;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-bar button:hover {
            background-color: #106ebe;
        }

        /* Task list styles */
        h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #292827;
        }

        ul {
            list-style: none;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        li {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.2s;
        }

        li:last-child {
            border-bottom: none;
        }

        li:hover {
            background-color: #f8f9fa;
        }

        li small {
            color: #666;
            font-size: 0.875rem;
        }

        /* Footer styles */
        footer {
            background-color: #fff;
            padding: 1.5rem;
            text-align: center;
            color: #666;
            border-top: 1px solid #e0e0e0;
            margin-top: 3rem;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            header {
                padding: 1rem;
            }

            .container {
                padding: 0 1rem;
            }

            .search-bar {
                flex-direction: column;
            }

            .search-bar button {
                width: 100%;
            }

            li {
                flex-direction: column;
                align-items: flex-start;
            }

            li small {
                margin-top: 0.5rem;
            }
        }

        /* AI Assistant Styles */
        .assistant-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #0078d4;
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .chat-popup {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 20px;
        }

        .chat-popup h3 {
            margin-bottom: 10px;
            color: #0078d4;
        }

        .chat-popup input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        .chat-popup button {
            background-color: #0078d4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-popup button:hover {
            background-color: #106ebe;
        }

        .chat-history {
            max-height: 150px;
            overflow-y: auto;
            margin-bottom: 10px;
        }

        .chat-message {
            margin-bottom: 10px;
        }

        .assistant-message {
            color: #0078d4;
        }

        .user-message {
            color: #292827;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Dashboard</h1>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Add Task Form -->
        <div class="search-bar">
            <input type="text" id="taskInput" placeholder="Enter task...">
            <button onclick="addTask()">Add Task</button>
        </div>

        <!-- Task List -->
        <h2>Tasks</h2>
        <ul id="taskList">
            <!-- Dynamically added tasks will appear here -->
        </ul>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Your Company. All Rights Reserved.</p>
    </footer>

    <!-- AI Assistant Button -->
    <button class="assistant-btn" onclick="toggleChatPopup()">🤖</button>

    <!-- Chat Popup -->
    <div class="chat-popup" id="chatPopup">
        <h3>AI Assistant</h3>
        <div class="chat-history" id="chatHistory"></div>
        <input type="text" id="userMessage" placeholder="Ask me anything...">
        <button onclick="sendMessage()">Send</button>
    </div>

    <!-- JavaScript for dynamic task addition and AI Assistant -->
    <script>
        // Function to add tasks to the list
        function addTask() {
            const taskInput = document.getElementById('taskInput');
            const taskList = document.getElementById('taskList');
            
            // Get the task entered by the user
            const taskText = taskInput.value.trim();
            
            // If the task input is empty, do nothing
            if (taskText === '') {
                alert('Please enter a task.');
                return;
            }
            
            // Create a new list item for the task
            const listItem = document.createElement('li');
            listItem.innerHTML = `${taskText} <small>Due: Not set</small>`;
            
            // Append the new task to the task list
            taskList.appendChild(listItem);
            
            // Clear the input field after adding the task
            taskInput.value = '';
        }

        // Function to toggle the AI Assistant chat popup
        function toggleChatPopup() {
            const chatPopup = document.getElementById('chatPopup');
            chatPopup.style.display = chatPopup.style.display === 'block' ? 'none' : 'block';
        }

        // Function to send a message to the AI Assistant
        function sendMessage() {
            const userMessage = document.getElementById('userMessage').value;
            const chatHistory = document.getElementById('chatHistory');
            
            if (userMessage.trim() === '') {
                alert('Please enter a question!');
                return;
            }
            
            // Display user's message
            const userMessageElement = document.createElement('div');
            userMessageElement.classList.add('chat-message', 'user-message');
            userMessageElement.innerText = `You: ${userMessage}`;
            chatHistory.appendChild(userMessageElement);

            // AI response (simulated with simple responses based on keywords)
            const aiMessage = generateAIResponse(userMessage);
            
            const aiMessageElement = document.createElement('div');
            aiMessageElement.classList.add('chat-message', 'assistant-message');
            aiMessageElement.innerText = `AI: ${aiMessage}`;
            chatHistory.appendChild(aiMessageElement);

            // Scroll chat history to the bottom
            chatHistory.scrollTop = chatHistory.scrollHeight;

            // Clear input field
            document.getElementById('userMessage').value = '';
        }

        // Function to generate AI response based on the message
        function generateAIResponse(message) {
            const lowerMessage = message.toLowerCase();
            
            // Basic simulated responses
            if (lowerMessage.includes('hello')) {
                return "Hi there! How can I assist you today?";
            } else if (lowerMessage.includes('task')) {
                return "Sure, I can help you manage your tasks!";
            } else if (lowerMessage.includes('help')) {
                return "You can ask me about tasks, project management, or any guidance you need!";
            } else {
                return "Sorry, I don't understand that. Can you please rephrase?";
            }
        }
    </script>

</body>
</html>
