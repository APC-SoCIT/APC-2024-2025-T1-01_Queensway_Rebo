<style>
    /* Floating Chatbot Styles */
    #chat-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        #chat-box {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 300px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        #chat-box .p-3 {
            padding: 10px;
        }

        #chat-content {
            max-height: 200px;
            overflow-y: auto;
        }

        #chat-input {
            width: 100%;
            padding: 8px;
        }
    </style>

 <!-- Floating Chat Button -->
 <div id="chat-toggle">
        <button class="btn btn-primary rounded-circle"><i class="bi bi-chat-dots"></i></button>
    </div>

    <!-- Floating Chatbox -->
    <div id="chat-box">
        <div class="p-3 border-bottom">
            <strong>FAQ Chatbot</strong>
        </div>
        <div id="chat-content" class="p-3"></div>
        <form id="chat-form" class="p-3 border-top">
            <input type="text" id="chat-input" class="form-control mb-2" placeholder="Ask a question..." autocomplete="off">
            <button type="submit" class="btn btn-primary w-100">Send</button>
        </form>
    </div>

    <script>
    let chatOpened = false; // Track if chat has been opened before

    // Chatbot toggle visibility
    document.getElementById('chat-toggle').addEventListener('click', function() {
        const chatBox = document.getElementById('chat-box');
        const chatContent = document.getElementById('chat-content');

        if (chatBox.style.display === 'none') {
            chatBox.style.display = 'block';

            // Show welcome message only on first open
            if (!chatOpened) {
                chatContent.innerHTML += `<div><strong>Bot:</strong> Hello! 👋 How can I help you today?</div>`;
                chatOpened = true;
            }
        } else {
            chatBox.style.display = 'none';
        }
    });

    // Handle form submission
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const question = document.getElementById('chat-input').value.trim();
        const chatContent = document.getElementById('chat-content');

        if (!question) return;

        chatContent.innerHTML += `<div><strong>You:</strong> ${question}</div>`;

        fetch("{{ route('faq.bot') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ question })
        })
        .then(res => res.json())
        .then(data => {
            chatContent.innerHTML += `<div><strong>Bot:</strong> ${data.answer}</div>`;
            document.getElementById('chat-input').value = '';
            chatContent.scrollTop = chatContent.scrollHeight;
        });
    });
</script>
