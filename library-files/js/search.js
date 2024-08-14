document.addEventListener('DOMContentLoaded', function () {
    const searchUser = document.getElementById('searchUser');
    const userList = document.getElementById('userList');
    const chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
    const chatBody = document.getElementById('chatBody');
    const messageInput = document.getElementById('messageInput');
    let currentReceiver = '';

    function liveSearch() {
        const query = searchUser.value.trim();
        if (query.length === 0) {
            userList.style.display = 'none';
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'chat.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const users = JSON.parse(xhr.responseText);
                userList.innerHTML = '';
                if (users.length > 0) {
                    users.forEach(user => {
                        const userItem = document.createElement('div');
                        userItem.className = 'user-item';
                        userItem.innerHTML = `<strong>${user.name}</strong><br><small>${user.username}</small>`;
                        userItem.addEventListener('click', function () {
                            document.getElementById('chatModalLabel').innerText = user.name;
                            currentReceiver = user.username; // Save the selected receiver's username
                            chatModal.show();
                            searchUser.value = '';
                            userList.style.display = 'none';
                            loadMessages(currentReceiver); // Load messages for the selected user
                        });
                        userList.appendChild(userItem);
                    });
                    userList.style.display = 'block';
                } else {
                    userList.style.display = 'none';
                }
            }
        };
        xhr.send('search_query=' + encodeURIComponent(query));
    }

    function loadMessages(receiver) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'chat.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const messages = JSON.parse(xhr.responseText);
                chatBody.innerHTML = '';
                messages.forEach(msg => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = msg.sender === currentReceiver ? 'message received' : 'message sent';
                    messageDiv.innerHTML = `<div class="content">${msg.message}</div>`;
                    chatBody.appendChild(messageDiv);
                });
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        };
        xhr.send('fetch_messages=1&receiver=' + encodeURIComponent(receiver));
    }

    function sendMessage() {
        const message = messageInput.value.trim();
        if (message === '') return;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'chat.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Display the sent message only if it was successfully inserted into the database
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message sent';
                    messageDiv.innerHTML = `<div class="content">${message}</div>`;
                    chatBody.appendChild(messageDiv);
                    messageInput.value = '';
                    chatBody.scrollTop = chatBody.scrollHeight;
                } else {
                    alert('Message could not be sent. Please try again.');
                }
            } else {
                alert('An error occurred. Please try again.');
            }
        };
        xhr.send('send_message=1&receiver=' + encodeURIComponent(currentReceiver) + '&message=' + encodeURIComponent(message));
    }

    window.liveSearch = liveSearch;
    window.sendMessage = sendMessage;
    window.loadMessages = loadMessages;
});
