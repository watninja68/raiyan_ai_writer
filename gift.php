<?php $pageTitle = "Gift"; ?>
<?php require_once 'layout/header.php'; ?>

    <div class="flex flex-col md:flex-row">

       <!-- Sidebar -->
       <?php require_once 'layout/sidebar.php'; ?>

        <!-- Main Content -->
        <main id="mainContent" class="main-content md:p-6 flex-1 ">
            <!-- Header -->
            <?php require_once 'layout/main-header.php'; ?> 

            <!-- Breadcrumb -->
            <?php include 'breadcrumb.php'; ?>


            <div class="container mx-auto px-4 py-10">
                <h1 class="text-2xl font-bold mb-2">Gift and offer</h1>
                <p class="text-gray-400  dark:text-black mb-6">Check all the gift that we have for you</p>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Offer 1 -->
                    <div onclick="toggleDetails('offer1')"
                        class="p-5 glass-card cursor-pointer dark:shadow-none dark:border dark:border-gray-700 hover:shadow-lg hover:border-cyan-400 transition-all duration-300 group relative transform hover:-translate-y-2 h-full">

                        <div
                            class="absolute inset-0 bg-gradient-to-br from-cyan-400/10 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-all duration-500">
                        </div>

                        <!-- Title Icon -->
                        <div class="flex flex-col gap-3 mb-4">
                            <h2 class="text-xl font-semibold">Exclusive Gift Box <span
                                    class="inline-block animate-pulse">🎀</span> </h2>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-300 dark:text-black text-sm mb-5">
                            Get 50% off on our premium plan when you sign up today. Limited time offer!
                        </p>

                        <!-- Learn More -->
                        <button class="text-cyan-400 dark:text-black hover:text-cyan-300"
                            onclick="toggleDetails('offer1')">Learn
                            More</button>
                    </div>

                    <!-- Offer 2 -->
                    <div onclick="toggleDetails('offer2')"
                        class="p-5 glass-card cursor-pointer dark:shadow-none dark:border dark:border-gray-700 hover:shadow-lg hover:border-cyan-400 transition-all duration-300 group relative transform hover:-translate-y-2 h-full">

                        <div
                            class="absolute inset-0 bg-gradient-to-br from-cyan-400/10 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-all duration-500">
                        </div>

                        <!-- Title Icon -->
                        <div class="flex flex-col gap-3 mb-4">
                            <h2 class="text-xl font-semibold">Limited-Time Discount <span
                                    class="inline-block animate-pulse">🎉</span> </h2>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-300 dark:text-black text-sm mb-5">
                            Get 30% off on all premium memberships.
                        </p>

                        <!-- Learn More -->
                        <button class="text-cyan-400 dark:text-black hover:text-cyan-300"
                            onclick="toggleDetails('offer2')">Learn
                            More</button>
                    </div>

                    <!-- Offer 3 -->
                    <div onclick="openModal('VIP Lounge Access', 'Experience exclusive events and behind-the-scenes content Experience exclusive events and behind-the-scenes content Experience exclusive events and behind-the-scenes content')"
                        class="p-5 glass-card cursor-pointer dark:shadow-none dark:border dark:border-gray-700 hover:shadow-lg hover:border-cyan-400 transition-all duration-300 group relative transform hover:-translate-y-2 h-full">

                        <div
                            class="absolute inset-0 bg-gradient-to-br from-cyan-400/10 to-transparent opacity-0 group-hover:opacity-100 rounded-2xl transition-all duration-500">
                        </div>

                        <!-- Title Icon -->
                        <div class="flex flex-col gap-3 mb-4">
                            <h2 class="text-xl font-semibold">VIP Lounge Access <span
                                    class="inline-block animate-pulse">🔥</span> </h2>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-300 dark:text-black text-sm mb-5">
                            Experience exclusive events and behind-the-scenes content
                        </p>

                        <!-- Learn More -->
                        <button class="text-cyan-400 dark:text-black hover:text-cyan-300"
                            onclick="toggleDetails('offer3')">Learn
                            More</button>
                    </div>

                </div>

                <!-- Modal -->
                <div id="offerModal"
                    class="fixed z-50 inset-0 bg-black/60  dark:text-white backdrop-blur-md flex items-center justify-center hidden">
                    <div class="bg-gray-900 p-8 rounded-lg shadow-xl transform scale-90 opacity-0 transition-all duration-300 w-2/3 relative"
                        id="modalContent">
                        <button onclick="closeModal()"
                            class="absolute top-3 right-3 text-gray-300 hover:text-red-400">✖</button>
                        <h2 id="modalTitle" class="text-2xl font-bold"></h2>
                        <p id="modalDescription" class="text-gray-300 mt-4"></p>
                    </div>
                </div>
                <!-- Hidden Details Section -->
                <div id="offer1" class="details hidden mt-6 glass-card p-6 rounded-lg">
                    <h3 class="text-2xl font-bold">🎀 Exclusive Gift Box</h3>
                    <p class="text-gray-300 mt-2">This special package contains premium goodies, exclusive merchandise,
                        and surprises curated just for you!</p>
                    <video class="mt-4 w-full rounded-lg" controls>
                        <source src="giftbox.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <div id="offer2" class="details hidden mt-6 glass-card p-6 rounded-lg">
                    <h3 class="text-2xl font-bold">🎉 Limited-Time Discount</h3>
                    <p class="text-gray-300 mt-2">Enjoy a 30% discount on all premium memberships for a limited time.
                        Don't miss out!</p>
                    <img src="discount.jpg" class="mt-4 w-full rounded-lg">
                </div>

                <div id="offer3" class="details hidden mt-6 glass-card p-6 rounded-lg">
                    <h3 class="text-2xl font-bold">🔥 VIP Lounge Access</h3>
                    <p class="text-gray-300 mt-2">Join the VIP club and experience exclusive events, early access to
                        features, and more!</p>
                    <iframe class="mt-4 w-full h-64 rounded-lg" src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                        allowfullscreen></iframe>
                </div>
            </div>



        </main>
    </div>
<?php require_once 'layout/footer.php'; ?>