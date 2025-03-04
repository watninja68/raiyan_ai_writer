<?php
$currentPage = basename($_SERVER['PHP_SELF']); // Get the current page name
?>
<!-- Sidebar -->
<aside id="sidebar" onmouseover="expandOnHover()" onmouseleave="collapseOnLeave()"
    class="sidebar bg-gray-800 p-4  flex flex-col z-50 space-y-3 md:h-full h-screen fixed transform -translate-x-full md:translate-x-0 transition-transform duration-300 ">
    <div class="flex item-center justify-between">
        <div class="flex justify-between item-center">
            <div class="" style="width: 40px; height: 45px;"><svg width="100%" height="100%"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 128.98 113.78">
                    <defs>
                        <linearGradient id="linear-gradient-396" x1="0.99" y1="49.81" x2="104.18" y2="108.06"
                            gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-color="#91f24a"></stop>
                            <stop offset="0.64" stop-color="#2bc7d4"></stop>
                            <stop offset="0.72" stop-color="#2ac3d3"></stop>
                            <stop offset="0.8" stop-color="#28b7d0"></stop>
                            <stop offset="0.88" stop-color="#25a3cb"></stop>
                            <stop offset="0.96" stop-color="#2088c4"></stop>
                            <stop offset="1" stop-color="#1d75bf"></stop>
                        </linearGradient>
                        <linearGradient id="linear-gradient-396-2" x1="31.47" y1="-4.18" x2="134.66" y2="54.06"
                            xlink:href="#linear-gradient-396"></linearGradient>
                        <linearGradient id="linear-gradient-396-3" x1="-5.65" y1="61.59" x2="97.53" y2="119.83"
                            xlink:href="#linear-gradient-396"></linearGradient>
                        <linearGradient id="linear-gradient-396-4" x1="19.48" y1="17.06" x2="122.67" y2="75.3"
                            xlink:href="#linear-gradient-396"></linearGradient>
                    </defs>
                    <g id="Creaitor_Mark_396" data-name="Creaitor_Mark396">
                        <g id="Creaitor_Mark_Layer_396" data-name="Creaitor_Mark_Layer396">
                            <circle cx="13.53" cy="56.89" r="13.53" style="fill: url(&quot;#linear-gradient-396&quot;);">
                            </circle>
                            <path
                                d="M88.26,0a13.43,13.43,0,0,0-7.18,2.07L81,2.14c-.56.46-1.14.9-1.73,1.32A28.25,28.25,0,0,1,62.84,8.67,28.24,28.24,0,0,1,46.46,3.46c-.47-.33-.94-.68-1.39-1l-.34-.27a13.53,13.53,0,1,0-.46,23.05c.26-.23.53-.45.8-.67.61-.48,1.23-.95,1.88-1.39a28.42,28.42,0,0,1,31.79,0,28.71,28.71,0,0,1,2.34,1.77l.33.29A13.53,13.53,0,1,0,88.26,0Z"
                                style="fill: url(&quot;#linear-gradient-396-2&quot;);"></path>
                            <path
                                d="M88.26,86.73a13.42,13.42,0,0,0-7.18,2.06l-.12.08c-.56.46-1.14.9-1.73,1.32a28.41,28.41,0,0,1-32.77,0c-.47-.34-.94-.69-1.39-1l-.34-.27a13.53,13.53,0,1,0-.46,23l.8-.66c.61-.49,1.23-1,1.88-1.39a28.42,28.42,0,0,1,31.79,0,26.6,26.6,0,0,1,2.34,1.77l.33.28a13.4,13.4,0,0,0,6.85,1.87,13.53,13.53,0,1,0,0-27Z"
                                style="fill: url(&quot;#linear-gradient-396-3&quot;);"></path>
                            <path
                                d="M115.46,43.36a13.49,13.49,0,0,0-7.19,2.07l-.11.07c-.56.47-1.14.9-1.74,1.32a28.37,28.37,0,0,1-32.77,0c-.47-.33-.94-.68-1.39-1l-.34-.28a13.52,13.52,0,1,0-.45,23c.26-.22.52-.45.79-.66.61-.49,1.23-1,1.88-1.4a28.42,28.42,0,0,1,31.79,0,25.63,25.63,0,0,1,2.34,1.77l.34.29a13.53,13.53,0,1,0,6.85-25.19Z"
                                style="fill: url(&quot;#linear-gradient-396-4&quot;);"></path>
                        </g>
                    </g>
                </svg></div>
                <div class="p-3 sidebar-text" style="font-size: 1.25rem; color: #2bc7d4;"> Logo </div>
        </div>
        <!-- Toggle Button -->
        <div class="toggle-btn cursor-pointer" onclick="sidebarCollapse()">
            <i id="toggleIcon" class="fas hidden md:block fa-chevron-left text-blue-400"></i>
        </div>
    </div>
    <nav class="flex flex-col pr-4 overflow-y-auto overflow-hidden">
        <a href="dashboard.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt text-lg w-6 text-center"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>
        <div>
            <button onclick="toggleSubMenu('submenu1');" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white w-full text-left">
                <i class="fas fa-magic text-lg w-6 text-center"></i>
                <span class="sidebar-text">Blog Wizard</span>
                <i class="fas sidebar-text fa-chevron-down ml-auto"></i>
            </button>
            <div id="submenu1" class="sidebar-text-hidden w-[100%] submenu hidden flexT flex-col pl-4 mt-2">
                <a href="guidedArticle.php" class="dark:text-gray-700 dark:hover:bg-cyan-100 dark:hover:bg-opacity-10 dark:hover:text-gray-500 submenu-link text-gray-400 hover:text-white <?= $currentPage == 'guidedArticle.php' ? 'active' : '' ?>">
                    <span>Guided Content Creation
                    </span>
                </a>
                <a href="oneClickArticle.php" class="dark:text-gray-700 dark:hover:bg-cyan-100 dark:hover:bg-opacity-10 dark:hover:text-gray-500 submenu-link text-gray-400 hover:text-white <?= $currentPage == 'oneClickArticle.php' ? 'active' : '' ?>">
                    <span>One Click Content Creation</span>
                </a>
            </div>
        </div>
        <a href="aiassistance.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'aiassistance.php' ? 'active' : '' ?>">
            <i class="fas fa-robot text-lg w-6 text-center"></i>
            <span class="sidebar-text">AI Assistance</span>
        </a>
        <a href="aichat.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'aichat.php' ? 'active' : '' ?>">
            <i class="fas fa-comments text-lg w-6 text-center"></i>
            <span class="sidebar-text">AI Chat</span>
        </a>

        <div>
            <button onclick="toggleSubMenu('submenu2'); setActive(this);" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white w-full text-left">
                <i class="fas fa-tasks text-lg w-6 text-center"></i>
                <span class="sidebar-text">Productivity</span>
                <i class="fas fa-chevron-down sidebar-text ml-auto"></i>
            </button>
            <div id="submenu2" class="sidebar-text-hidden  hidden flex flex-col pl-4 mt-2">
                <a href="chatContent.php" class="dark:text-gray-700 dark:hover:bg-cyan-100 dark:hover:bg-opacity-10 dark:hover:text-gray-500 submenu-link text-gray-400 hover:text-white <?= $currentPage == 'chatContent.php' ? 'active' : '' ?>">
                    <span class="">30 days Content Maker
                    </span>
                </a>
                <a href="chatRelatedKeyword.php" class="dark:text-gray-700 dark:hover:bg-cyan-100 dark:hover:bg-opacity-10 dark:hover:text-gray-500 submenu-link text-gray-400 hover:text-white <?= $currentPage == 'chatRelatedKeyword.php' ? 'active' : '' ?>">
                    <span class="">Related keywords finder</span>
                </a>
                <a href="chatGrammer.php" class="dark:text-gray-700 dark:hover:bg-cyan-100 dark:hover:bg-opacity-10 dark:hover:text-gray-500 submenu-link text-gray-400 hover:text-white <?= $currentPage == 'chatGrammer.php' ? 'active' : '' ?>">
                    <span class="">Grammar Improvement</span>
                </a>
                <a href="chatGenerator.php" class="dark:text-gray-700 dark:hover:bg-cyan-100 dark:hover:bg-opacity-10 dark:hover:text-gray-500 submenu-link text-gray-400 hover:text-white <?= $currentPage == 'chatGenerator.php' ? 'active' : '' ?>">
                    <span class="">AI Prompt Generator</span>
                </a>
                <a href="chatTrending.php" class="dark:text-gray-700 dark:hover:bg-cyan-100 dark:hover:bg-opacity-10 dark:hover:text-gray-500 submenu-link text-gray-400 hover:text-white <?= $currentPage == 'chatTrending.php' ? 'active' : '' ?>">
                    <span class="">Trending Topic finder</span>
                </a>
            </div>
        </div>
        <a href="gift.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'gift.php' ? 'active' : '' ?>">
            <i class="fas fa-gift text-lg w-6 text-center"></i>
            <span class="sidebar-text">Gift and Offer</span>
        </a>
        <a href="tutorial.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'tutorial.php' ? 'active' : '' ?>">
            <i class="fas fa-graduation-cap text-lg w-6 text-center"></i>
            <span class="sidebar-text">Tutorial</span>
        </a>
        <a href="editor.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'editor.php' ? 'active' : '' ?>">
            <i class="fas fa-edit text-lg w-6 text-center"></i>
            <span class="sidebar-text">Editor</span>
        </a>
        <a href="output.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'output.php' ? 'active' : '' ?>">
            <i class="fas fa-file-export text-lg w-6 text-center"></i>
            <span class="sidebar-text">output</span>
        </a>
        <a href="affiliate.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'affiliate.php' ? 'active' : '' ?>">
            <i class="fas fa-hand-holding-usd text-lg w-6 text-center"></i>
            <span class="sidebar-text">Earn as an Affiliate</span>
        </a>
        <a href="support.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'support.php' ? 'active' : '' ?>">
            <i class="fas fa-ticket-alt text-lg w-6 text-center"></i>
            <span class="sidebar-text">Support Ticket</span>
        </a>
        <a href="contactus.php" class="sidebar-link dark:text-gray-900 text-gray-300 hover:text-white <?= $currentPage == 'contactus.php' ? 'active' : '' ?>">
            <i class="fas fa-envelope text-lg w-6 text-center"></i>
            <span class="sidebar-text">contact Us</span>
        </a>
    </nav>
</aside>