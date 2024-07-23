// Sample data (simulating message history)
const messageHistory = {
    conversation1: [
        { sender: 'User 1', timestamp: '10:00 AM', content: 'Hello! How are you?' },
        { sender: 'User 2', timestamp: '10:05 AM', content: 'I\'m good, thanks! How about you?' }
        // Add more messages as needed
    ],
    conversation2: [
        { sender: 'User 1', timestamp: '11:00 AM', content: 'Hey there! What\'s up?' },
        { sender: 'User 2', timestamp: '11:05 AM', content: 'Not much, just chilling.' }
        // Add more messages as needed
    ]
};

// Function to display message history for a conversation
function displayMessageHistory(conversationId) {
    const messageContainer = document.getElementById('messageContainer');
    messageContainer.innerHTML = ''; // Clear existing messages

    messageHistory[conversationId].forEach(message => {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        messageDiv.innerHTML = `
            <div class="sender">${message.sender}</div>
            <div class="timestamp">${message.timestamp}</div>
            <div class="content">${message.content}</div>
        `;
        messageContainer.appendChild(messageDiv);
    });
}

// Function to handle sending a message
function sendMessage(conversationId, message) {
    // Simulate sending message (you would implement server-side logic here)
    setTimeout(() => {
        const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        messageHistory[conversationId].push({ sender: 'You', timestamp: timestamp, content: message });
        displayMessageHistory(conversationId); // Update message history after sending message
    }, 500); // Simulate delay
}

// Event listener for clicking on a conversation
document.querySelectorAll('.conversation').forEach(conversation => {
    conversation.addEventListener('click', () => {
        // Highlight selected conversation
        document.querySelectorAll('.conversation').forEach(conversation => {
            conversation.classList.remove('active');
        });
        conversation.classList.add('active');

        // Display message history for selected conversation
        const conversationId = conversation.id;
        displayMessageHistory(conversationId);
    });
});

// Event listener for sending a message
document.getElementById('sendMessageBtn').addEventListener('click', () => {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    if (message !== '') {
        const activeConversation = document.querySelector('.conversation.active');
        const conversationId = activeConversation.id;
        sendMessage(conversationId, message);
        messageInput.value = ''; // Clear message input after sending
    }
});
