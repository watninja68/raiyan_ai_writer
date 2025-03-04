<?php
// Start session and include dependencies
session_start();
include 'db_init.php';
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$yourApiKey = $_ENV['QWEN_API'];

// Check if the user is logged in
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

/**
 * Store a chat message in the database including conversation_id.
 */
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

/**
 * Call the Qwen API with the user query and store the messages.
 * Since you don't need a conversation history, we'll use a fixed conversation ID.
 */
function chatWithQwen($client, PDO $pdo, $conversationId, $userInput, $userId) {
    // Save the user's query
    addChatMessage($pdo, session_id(), $conversationId, 'user', $userInput, $userId);
    
    // Prepare a minimal messages array (no conversation history)
    $messages = [
        ['role' => 'system', 'content' => 'You are a helpful AI assistant that provides trending topic insights.'],
        ['role' => 'user',   'content' => $userInput]
    ];
    
    try {
        $result = $client->chat()->create([
            'model'    => 'qwen-plus',
            'messages' => $messages
        ]);
        $assistantResponse = $result->choices[0]->message->content;
        // Save the assistant's reply
        addChatMessage($pdo, session_id(), $conversationId, 'assistant', $assistantResponse, $userId);
        return $assistantResponse;
    } catch (Exception $e) {
        $errorMsg = "Error: " . $e->getMessage();
        addChatMessage($pdo, session_id(), $conversationId, 'assistant', $errorMsg, $userId);
        return $errorMsg;
    }
}

// Use a fixed conversation id (since conversation history isn't needed)
$conversationId = 'default';

$aiResponse = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $userInput = trim($_POST['query']);
    if ($userInput !== "") {
        $client = OpenAI::factory()
            ->withApiKey($yourApiKey)
            ->withBaseUri('https://dashscope-intl.aliyuncs.com/compatible-mode/v1')
            ->make();
        $aiResponse = chatWithQwen($client, $pdo, $conversationId, $userInput, $_SESSION['google_email']);
    }
}
?>
<?php $pageTitle = "chat Trending Topic"; ?>
<?php require_once 'layout/header.php'; ?>

    <!-- Sidebar -->
    <?php require_once 'layout/sidebar.php'; ?>

  <div id="mainContent" class="main-content h-screen md:p-4 overflow-hidden md:ml-64">
     <!-- Header -->
     <?php require_once 'layout/main-header.php'; ?> 
    
     <div class="flex flex-col glass-card pl-3 flex-1 h-screen">
      <!-- Header -->
      <div class="flex justify-between p-2 items-center border-b dark:border-none border-gray-700/50">
        <!-- Toggle Button for Mobile -->
        <button id="sidebar-toggle" onclick="toggleChatSidebar()"
          class="md:hidden bg-cyan-500/80 px-2 py-1 rounded-lg hover:bg-cyan-400/80 transition-all duration-300 glow">
          <i class="fas fa-bars"></i>
        </button>
        <h1 class="text-2xl font-semibold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
          Trending Topic Finder
        </h1>
        <button class="bg-cyan-500/80 px-4 py-2 dark:text-white rounded-lg hover:bg-cyan-400/80 transition-all duration-300 glow">
          New Search
        </button>
      </div>
      <div class="flex flex-1 gap-2">
        <!-- Main Chat Section (60% width) -->
        <main class="md:w-1/3 bg-gray-800 dark:bg-white flex flex-col rounded border-gray-700 dark:border-gray-700 p-3 h-[80%] relative mb-10">
          <!-- Fixed Search Bar with AI Input -->
          <div class="sticky top-0 bg-gray-800/70 dark:bg-white backdrop-blur-md z-10 p-2">
            <form method="POST" action="">
              <input type="text" name="query" placeholder="Ask about trending topics..."
                class="w-full p-2 rounded-lg bg-gray-700/50 dark:text-black dark:bg-white dark:border-black text-white border border-gray-600 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:focus:ring-black transition-all duration-300">
            </form>
          </div>
          <!-- Display AI Response -->
          <?php if (!empty($aiResponse)): ?>
            <div class="mt-4 p-4 bg-gray-700/50 rounded-lg">
              <p class="text-white"><?php echo htmlspecialchars($aiResponse); ?></p>
            </div>
          <?php endif; ?>
        </main>
        <!-- Sidebar (40% width) -->
        <aside id="chatSidebar" class="chatSidebar flex-1 border-l bg-gray-800 dark:bg-white md:relative border-r hidden rounded border-gray-700 backdrop-blur-md p-4 md:flex h-[80%] flex-col">
          <!-- Additional content can go here -->
        </aside>
      </div>
    </div>
  </div>
  <script src="scripts/script.js"></script>
  <script>
    // Toggle sidebar for mobile
    function toggleChatSidebar() {
      const sidebar = document.getElementById('chatSidebar');
      if (sidebar.classList.contains('w-0')) {
        sidebar.classList.remove('w-0');
        sidebar.classList.add('w-3/4');
      } else {
        sidebar.classList.remove('w-3/4');
        sidebar.classList.add('w-0');
      }
    }
  </script>
</body>
</html>
<?php// require_once 'layout/footer.php'; ?>