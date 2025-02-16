<?php
include 'db_init.php';
require_once 'vendor/autoload.php';
session_start();


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$yourApiKey = $_ENV['QWEN_API'];

if (!isset($_SESSION['google_loggedin'])) {
    header('Location: login.php');
    exit;
}

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Set up the OpenAI client
$client = OpenAI::factory()
    ->withApiKey($yourApiKey)
    ->withBaseUri('https://dashscope-intl.aliyuncs.com/compatible-mode/v1')
    ->make();

$userId = $_SESSION['google_email'];

// Handle conversation ID
if (isset($_GET['conversation_id'])) {
    $conversationId = $_GET['conversation_id'];
    $_SESSION['conversation_id'] = $conversationId;
} else {
    if (isset($_SESSION['conversation_id'])) {
        $conversationId = $_SESSION['conversation_id'];
    } else {
        $conversationId = 'conv_' . uniqid('', true);
        $_SESSION['conversation_id'] = $conversationId;
        header("Location: " . $_SERVER['PHP_SELF'] . "?conversation_id=" . urlencode($conversationId));
        exit;
    } 
}

function getConversationHistory(PDO $pdo, $sessionId, $conversationId, $userId) {
    $stmt = $pdo->prepare("SELECT role, content FROM chat_messages 
                           WHERE session_id = :session_id AND conversation_id = :conversation_id AND user_id = :user_id
                           ORDER BY created_at ASC");
    $stmt->execute([
        ':session_id'      => session_id(),
        ':conversation_id' => $conversationId,
        ':user_id'         => $userId
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addChatMessage(PDO $pdo, $sessionId, $conversationId, $role, $content, $userId) {
    $stmt = $pdo->prepare("INSERT INTO chat_messages (session_id, conversation_id, role, content, user_id) 
                           VALUES (:session_id, :conversation_id, :role, :content, :user_id)");
    $stmt->execute([
        ':session_id'      => $sessionId,
        ':conversation_id' => $conversationId,
        ':role'            => $role,
        ':content'         => $content,
        ':user_id'         => $userId
    ]);
}

function chatWithQwen($client, PDO $pdo, $conversationId, $userInput, $userId) {
    addChatMessage($pdo, session_id(), $conversationId, 'user', $userInput, $userId);

    $conversationHistory = getConversationHistory($pdo, session_id(), $conversationId, $userId);
    $messages = [];
    $messages[] = [
        'role'    => 'system',
        'content' => 'You are a helpful Blog writter who helps in helping writting blogs for creative poeple'
    ];
    foreach ($conversationHistory as $message) {
        $messages[] = [
            'role'    => $message['role'],
            'content' => $message['content']
        ];
    }

    try {
        $result = $client->chat()->create([
            'model'    => 'qwen-plus',
            'messages' => $messages
        ]);

        $assistantResponse = $result->choices[0]->message->content;
        addChatMessage($pdo, session_id(), $conversationId, 'assistant', $assistantResponse, $userId);
        return $assistantResponse;
    } catch (Exception $e) {
        $errorMsg = "Error: " . $e->getMessage();
        addChatMessage($pdo, session_id(), $conversationId, 'assistant', $errorMsg, $userId);
        return $errorMsg;
    }
}

// Modified POST handling with PRG pattern
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userInput = trim($_POST['message']);
    if ($userInput !== '') {
        // Store the message in a session variable
        $_SESSION['pending_message'] = $userInput;
        // Redirect to the same page with the conversation ID
        header("Location: " . $_SERVER['PHP_SELF'] . "?conversation_id=" . urlencode($conversationId));
        exit;
    }
}

// Process pending message if it exists
if (isset($_SESSION['pending_message'])) {
    $userInput = $_SESSION['pending_message'];
    chatWithQwen($client, $pdo, $conversationId, $userInput, $userId);
    // Clear the pending message
    unset($_SESSION['pending_message']);
}

// Retrieve conversation history and conversations list
$chatHistory = getConversationHistory($pdo, session_id(), $conversationId, $userId);
$stmt = $pdo->prepare("SELECT DISTINCT conversation_id FROM chat_messages WHERE session_id = :session_id AND user_id = :user_id");
$stmt->execute([
    ':session_id' => session_id(),
    ':user_id'    => $userId
]);
$conversations = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat with Qwen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            display: flex;
            gap: 25px;
            padding: 0 20px;
        }

        .chat-container {
            flex: 3;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
        }

        .chat-container h2 {
            color: #1a1a1a;
            margin-bottom: 20px;
            font-size: 1.5rem;
            border-bottom: 2px solid #eef0f2;
            padding-bottom: 15px;
        }

        .chat-history {
            height: 500px;
            overflow-y: auto;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            border: 1px solid #e1e4e8;
        }

        .message {
            margin: 15px 0;
            max-width: 85%;
        }

        .user-message {
            background-color: #0084ff;
            color: white;
            border-radius: 18px;
            padding: 12px 18px;
            margin-left: auto;
            animation: slideLeft 0.3s ease;
        }

        .assistant-message {
            background-color: #f0f2f5;
            color: #1a1a1a;
            border-radius: 18px;
            padding: 12px 18px;
            margin-right: auto;
            animation: slideRight 0.3s ease;
        }

        .input-form {
            display: flex;
            gap: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 12px;
        }

        input[type="text"] {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #e1e4e8;
            border-radius: 25px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #0084ff;
            box-shadow: 0 0 0 2px rgba(0,132,255,0.2);
        }

        button {
            padding: 12px 25px;
            background-color: #0084ff;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #0073e6;
            transform: translateY(-1px);
        }

        .sidebar {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .sidebar h3 {
            color: #1a1a1a;
            margin-bottom: 20px;
            font-size: 1.2rem;
            border-bottom: 2px solid #eef0f2;
            padding-bottom: 15px;
        }

        .conversation-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .conversation-item:hover {
            background-color: #f8f9fa;
        }

        .conversation-link {
            text-decoration: none;
            color: #1a1a1a;
            flex-grow: 1;
            padding: 5px;
        }

        .delete-btn {
            background-color: #dc3545;
            padding: 8px 12px;
            font-size: 14px;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideLeft {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h3>Conversations</h3>
            <?php foreach ($conversations as $conv): ?>
                <div class="conversation-item">
                    <a class="conversation-link" href="?conversation_id=<?= htmlspecialchars($conv) ?>">
                        <?= htmlspecialchars($conv) ?>
                    </a>
                    <button class="delete-btn" data-conversation-id="<?= htmlspecialchars($conv) ?>">Delete</button>
                </div>
            <?php endforeach; ?>
            <br>
            <button onclick="window.location.href='new_chat.php'">New Conversation</button>
            <br><br>
        </div>

        <div class="chat-container">
            <h2>Chat with Qwen (Conversation: <?= htmlspecialchars($conversationId) ?>)</h2>
            <div class="chat-history" id="chatHistory">
                <?php if (!empty($chatHistory)): ?>
                    <?php foreach ($chatHistory as $message): ?>
                        <?php $class = $message['role'] === 'user' ? 'user-message' : 'assistant-message'; ?>
                        <div class="message <?= $class ?>">
                            <?= nl2br(htmlspecialchars($message['content'])) ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No messages yet.</p>
                <?php endif; ?>
            </div>
            <form method="POST" class="input-form">
                <input type="text" name="message" placeholder="Type your message..." required autofocus>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-scroll chat history to bottom
            var chatHistoryEl = document.getElementById('chatHistory');
            if (chatHistoryEl) {
                chatHistoryEl.scrollTop = chatHistoryEl.scrollHeight;
            }

            // Clear chat button
            document.getElementById('clearChatBtn').addEventListener('click', function() {
                clearChat();
            });

            // Add event listeners to all delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const convId = this.getAttribute('data-conversation-id');
                    deleteConversation(convId);
                });
            });
        });

        function clearChat() {
            if (confirm('Are you sure you want to clear the current conversation?')) {
                const currentConvId = '<?= htmlspecialchars($conversationId) ?>';
                fetch('clear_chat.php?conversation_id=' + encodeURIComponent(currentConvId))
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error clearing chat. Please try again.');
                    });
            }
        }

        function deleteConversation(convId) {
            if (confirm('Are you sure you want to permanently delete this conversation?')) {
                fetch('delete_convo.php?conversation_id=' + encodeURIComponent(convId))
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting conversation. Please try again.');
                    });
            }
        }
    </script>
</body>
</html>