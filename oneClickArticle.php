<?php
session_start();
include 'db_init.php';
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$yourApiKey = $_ENV['QWEN_API'];

// Optional login check – remove if not needed
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

// Functions to handle conversation and messages
function getConversationHistory(PDO $pdo, $sessionId, $conversationId, $userId) {
    $stmt = $pdo->prepare("SELECT role, content, created_at FROM chat_messages 
                           WHERE session_id = :session_id AND conversation_id = :conversation_id AND user_id = :user_id
                           ORDER BY created_at ASC");
    $stmt->execute([
        ':session_id'      => $sessionId,
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
        'content' => 'You are a helpful Blog writer who helps people create engaging content'
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

// Process the submitted article topic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userInput = trim($_POST['message']);
    if ($userInput !== '') {
        chatWithQwen($client, $pdo, $conversationId, $userInput, $userId);
    }
}

// Retrieve conversation history for display
$chatHistory = getConversationHistory($pdo, session_id(), $conversationId, $userId);
?>
<?php $pageTitle = "One Click Article"; ?>
<?php require_once 'layout/header.php'; ?>
   <!-- Sidebar -->
        <?php require_once 'layout/sidebar.php'; ?>

    <div id="mainContent" class="main-content h-screen md:p-4 overflow-hidden md:ml-64">
         <!-- Header -->
         <?php require_once 'layout/main-header.php'; ?> 
        <div class="p-8">
            <div class="mb-10">
                <h1 class="text-2xl font-bold mb-2">One Click Article Wizard</h1>
                <p class="text-gray-400 dark:text-black">Your step-by-step guide to crafting great content</p>
            </div>

            <!-- Article Topic Form with chat functionality -->
            <form method="POST">
                <div id="step-content" class="space-y-6 md:w-1/2">
                    <div class="bg-white/10 dark:from-gray-400/10 dark:to-transparent p-6 rounded-xl shadow-md">
                        <h3 class="text-lg dark:text-black font-bold">Article Topic</h3>
                        <p class="text-gray-400 dark:text-black">Let's get started.</p>
                        <textarea name="message" class="w-full p-3 rounded-md bg-gray-700 border-gray-600 text-white mt-3" rows="4"
                            placeholder="A Small Description about the article..."></textarea>

                        <button id="next-btn" type="submit"
                            class="px-6 py-3 bg-cyan-600 my-3 hover:bg-cyan-500 text-white font-semibold rounded-lg shadow-lg">Create →</button>
                    </div>
                </div>
            </form>

            <!-- Display the conversation history (chat messages) below the form -->
            <?php if (!empty($chatHistory)): ?>
            <div id="chat-content" class="mt-8 space-y-4">
                <?php foreach ($chatHistory as $message): ?>
                    <?php if ($message['role'] === 'user'): ?>
                        <div class="message-user">
                            <p><?php echo nl2br(htmlspecialchars($message['content'])); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="message-assistant">
                            <p><?php echo nl2br(htmlspecialchars($message['content'])); ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
    <?php require_once 'layout/footer.php'; ?>
