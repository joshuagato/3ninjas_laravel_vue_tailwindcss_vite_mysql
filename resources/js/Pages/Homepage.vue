<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import NavBar from "@/Components/NavBar.vue";
import Footer from "@/Components/Footer.vue";
import Analytics from "@/Components/Analytics.vue";
import Meaning from "@/Components/Meaning.vue";
import axios from "../axiosConfig";

const whiteNinja = ref(null);
const blackNinja = ref(null);
const redNinja = ref(null);
const previousResults = ref(null);
const topBanner = ref(null);
const bottomBanner = ref(null);
const whiteNinjaButtons = ref(null);
const blackNinaButtons = ref(null);
const redNinjaButtons = ref(null);
const usersCount = ref(null);
// const subscribersCount = ref(null);
const predictionsCount = ref(null);

let intervalTracker;
onMounted(() => {
    let accordionHeaders = document.querySelectorAll(
        "#accordion .item .header",
    );
    accordionHeaders.forEach((header) => {
        header.addEventListener("click", (event) => {
            // accordionHeaders.forEach(header => {
            //   header.closest(".item").classList.remove("accordion-active");
            // });
            event.currentTarget
                .closest(".item")
                .classList.toggle("accordion-active");
        });
    });

    const slides = document.querySelectorAll("#center .images .slide");
    const sliderButtons = document.querySelectorAll("#slider-buttons a");
    let counter = 0;

    intervalTracker = setInterval(() => {
        document.querySelector("#center .img1").style.marginLeft =
            counter * -20 + "%";
        sliderButtons[counter].classList.add("active-slide");

        const siblings = getSiblings(sliderButtons[counter]);
        siblings.forEach((sibling) => sibling.classList.remove("active-slide"));
        counter++;

        if (counter > slides.length - 1) {
            counter = 0;
        }
    }, 3000);

    function scrollToElement(elementId, offset = 100) {
        const element = document.getElementById(elementId);
        if (element) {
            // Apply scroll-margin-top to the element
            element.style.scrollMarginTop = `${offset}px`;

            // Smoothly scroll the element into view
            element.scrollIntoView({ behavior: "smooth" });
            element.classList.add("accordion-active");
        }
    }

    setTimeout(() => {
        const { url } = usePage();
        if (url.includes("default")) {
            const ninja = url.split("=")[1];
            if (ninja && ninja === "previous")
                scrollToElement("previous-results");
            if (ninja && ninja === "red") scrollToElement("red-ninja");
            if (ninja && ninja === "white") scrollToElement("white-ninja");
            if (ninja && ninja === "black") scrollToElement("black-ninja");
        }
    }, 2500);
});

const getSiblings = (element) => {
    const parent = element.parentNode;

    // Filter out the original element from the list of all children
    const siblings = Array.from(parent.children).filter(
        (child) => child !== element,
    );

    return siblings;
};

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const getBanner = (bannersList, position) =>
    bannersList.filter((banner) => banner.position === position)[0];
const getNinjaButtons = (buttonList, ninja) =>
    buttonList.filter((button) => button.ninja === ninja);

const getNinjas = async () => {
    try {
        let response;

        response = await axios.get("users-count");
        usersCount.value = response.data;

        // response = await axios.get('subscriptions-count');
        // subscribersCount.value = response.data;

        response = await axios.get("predictions-count");
        predictionsCount.value = response.data;

        response = await axios.get("banners");
        topBanner.value = getBanner(response.data, "top");
        bottomBanner.value = getBanner(response.data, "bottom");

        response = await axios.get("white-ninjas");
        whiteNinja.value = response.data;

        response = await axios.get("black-ninjas");
        blackNinja.value = response.data;

        response = await axios.get("red-ninjas");
        redNinja.value = response.data;

        response = await axios.get("previous-results");
        previousResults.value = response.data;

        response = await axios.get("buttons");
        const buttonsArray = response.data;
        whiteNinjaButtons.value = getNinjaButtons(buttonsArray, "white");
        blackNinaButtons.value = getNinjaButtons(buttonsArray, "black");
        redNinjaButtons.value = getNinjaButtons(buttonsArray, "red");
        // buttonsArray.sort((a, b) => a.priority - b.priority);
        // buttons.value = buttonsArray;
    } catch (error) {
        console.error("There was an error fetching the data: ", error);
    }
};

getNinjas();

const sliderImages = ["/images/bg_1.jpg", "/images/bg_2.jpg"];

const slideImage = (event) => {
    const targetElement = event.target;
    const btnNumber = event.target.classList.value
        .split(" ")[0]
        .split("btn")[1];
    const marginValue = (btnNumber - 1) * -20;

    const siblings = getSiblings(targetElement);
    targetElement.classList.add("active-slide");
    siblings.forEach((sibling) => sibling.classList.remove("active-slide"));
    document.querySelector("#center .img1").style.marginLeft =
        `${marginValue}%`;
};

onUnmounted(() => {
    clearInterval(intervalTracker);
});

const reportUserClick = async (user_id, ninja, company) => {
    const response = await axios.post("bet-button-clicks", {
        user_id,
        ninja,
        company,
    });

    if (response.status === 201) console.log("Clicked");
};
</script>

<template>
    <Head>
        <title>Homepage</title>
        <meta
            name="description"
            content="Join 3NinjasOdds for daily expert betting tips and accurate odds delivered directly to your phone via SMS or email. Get exclusive VIP slips and personalized recommendations to enhance your betting strategy."
        />
    </Head>
    <!-- <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50"> -->
    <div
        class="h-screen w-screen overflow-x-hidden bg-[#EEF5FF] scroll-smooth fade-in"
    >
        <!-- Navbar Section -->
        <NavBar />

        <!-- Header Section -->
        <!-- <header class="w-[100%] h-screen bg-center bg-cover text-white flex items-center justify-center 
      md:rounded-br-[15%] rounded-br-[10px]">
        <div class="header-content mb-[150px] text-whitesmoke text-center">
          <h1 class="text-[7vmin] mt-[120px] md:mt-[70px]">Welcome to 3Ninjas Hub</h1>
          <div class="line w-[150px] h-[4px] bg-[#fc036b] my-[10px] mx-auto rounded-[5px]"></div>
          <h2 class="text-[4vmin] mt-[50px] mb-[50px]">Get insights for betting.</h2>
          <Link href="/" class="no-underline bg-[#fc036b] rounded-[30px] text-[#FFF] py-[8px] px-[15px]">Learn more</Link>
        </div>
      </header> -->

        <div id="center">
            <div class="images">
                <!-- <div class="slide img1"> -->
                <div
                    v-for="(image, index) in sliderImages"
                    :key="index"
                    class="slide from-top"
                    :class="`${index === 0 ? 'img1' : ''}`"
                >
                    <div
                        class="bg-image"
                        :style="{
                            backgroundImage:
                                'linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url(' +
                                image +
                                ')',
                            backgroundRepeat: 'no-repeat',
                            backgroundPosition: 'center center',
                            backgroundSize: 'cover',
                        }"
                    >
                        <div
                            class="header-content mb-[150px] text-whitesmoke text-center"
                        >
                            <h1 class="text-[7vmin] mt-[120px] md:mt-[70px]">
                                Welcome to 3Ninjas Hub
                            </h1>
                            <div
                                class="line w-[150px] h-[4px] bg-[#fc036b] my-[10px] mx-auto rounded-[5px]"
                            ></div>
                            <h2 class="text-[4vmin] mt-[50px] mb-[50px]">
                                Get insights for betting.
                            </h2>
                            <Link
                                href="/"
                                class="no-underline bg-[#fc036b] rounded-[30px] text-[#FFF] py-[8px] px-[15px]"
                                >Learn more</Link
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div id="slider-buttons">
                <a
                    href="#"
                    v-for="(btn, index) in sliderImages"
                    :key="index"
                    @click="slideImage($event)"
                    :class="`btn${index + 1}`"
                ></a>
            </div>
        </div>

        <!-- Top Banner Section -->
        <section
            v-if="topBanner?.id"
            class="w-full my-10 lg:my-15 flex justify-center"
        >
            <a
                :href="topBanner?.url"
                target="_blank"
                class="w-[570px] lg:w-[970px] h-auto lg:h-[250px]"
            >
                <img
                    :src="`/banners/${topBanner?.filename}`"
                    alt="top-banner"
                    class="max-w-full max-h-full"
                />
            </a>
        </section>

        <!-- Title Section -->
        <!-- <section class="w-full lg:w-[60%] flex flex-col items-center px-2"> -->
        <section class="w-full lg:w-full flex flex-col items-center px-2">
            <h2
                class="text-black my-3 font-extrabold text-center text-[18px] lg:text-[32px]"
            >
                FREE PREDICTIONS (LIMITED)
            </h2>
            <p
                class="bg-custom-gray my p-3 font-extrabold text-center text-[12px] lg:text-[16px] text-gray-300"
            >
                NB: These free Predictions are limited. Log In or Sign Up to
                enjoy more Free slips with higher returns
            </p>
        </section>

        <!-- Ninjas Section -->
        <!-- <section id="accordion" class="w-screen flex justify-start px-1 my-10 h-auto text-white"> -->
        <!-- <div class="w-full lg:w-[60%] rounded-lg overflow-hidden bg-slate-800 flex flex-col gap-[3px] lg:mx-5"> -->
        <section
            id="accordion"
            class="w-screen flex justify-center px-1 my-10 h-auto text-white"
        >
            <div
                class="w-full lg:w-[80%] rounded-lg overflow-hidden bg-slate-800 flex flex-col gap-[3px] lg:mx-5"
            >
                <!-- Previous Predictions -->
                <div id="previous-results" class="item accordion-active">
                    <div
                        class="header p-6 bg-slate-900 font-bold flex justify-between item-center cursor-pointer"
                    >
                        <div>
                            <div>PREVIOUS RESULTS</div>
                            <div>Prediction Results</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-up active-icon"></i>
                            <i class="fas fa-chevron-down inactive-icon"></i>
                        </div>
                    </div>
                    <div
                        class="content bg-slate-800 w-full transition-all duration-500"
                    >
                        <div class="mb-2">
                            <table class="shadow-2xl border md:border-2 w-full">
                                <thead class="text-black">
                                    <tr class="text-left">
                                        <th class="py-3 px-2 bg-cyan-200">#</th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            League
                                        </th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            Fixtures
                                        </th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            Tips
                                        </th>
                                        <th
                                            class="py-3 px-2 bg-cyan-200 text-center"
                                        >
                                            Results
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-300">
                                    <tr
                                        v-for="(item, index) in previousResults"
                                        :key="item.id"
                                        class="text-left text-left text-[11px] sm:text-[13px] md:text-[16px]"
                                    >
                                        <td class="py-3 px-2">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.league?.title }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.fixtures }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.tip?.title }}
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <i
                                                v-if="item.results === true"
                                                class="far fa-check-square"
                                                style="color: green"
                                            ></i>
                                            <i
                                                v-if="item.results === false"
                                                class="fas fa-times"
                                                style="color: red"
                                            ></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div
                                class="p-4 w-full flex flex-col items-center gap-10"
                            >
                                <!-- <div class="flex flex-col lg:flex-row gap-4 flex-wrap justify-center">
                    <a v-for="button in buttons" :key="button.id" :href="button.url" :style="{ backgroundColor: button.background, color: button.foreground }" 
                      :class="`no-underline uppercase rounded-[30px] text-[13px] font-bold py-[3px] px-[45px]`"
                      target="_blank">
                      {{ button.title }}
                    </a>
                  </div> -->
                                <Link
                                    v-if="!$page.props.auth.user"
                                    class="no-underline bg-cyan-200 text-[black] rounded-[5px] text-[13px] font-bold text-whitesmoke py-[10px] px-[45px]"
                                    target="_blank"
                                    href="/login"
                                >
                                    LOGIN FOR THIS BETCODE
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Red Ninja -->
                <div id="red-ninja" class="item accordion-active">
                    <div
                        class="header p-6 bg-slate-900 font-bold flex justify-between item-center cursor-pointer"
                    >
                        <div>
                            <div>RED NINJA</div>
                            <div>High Risk, Big Reward</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-up active-icon"></i>
                            <i class="fas fa-chevron-down inactive-icon"></i>
                        </div>
                    </div>
                    <div
                        class="content bg-slate-800 w-full transition-all duration-500"
                    >
                        <div class="mb-2">
                            <table class="shadow-2xl border md:border-2 w-full">
                                <thead class="text-black">
                                    <tr class="text-left">
                                        <th class="py-3 px-2 bg-cyan-200">#</th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            League
                                        </th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            Fixtures
                                        </th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            Tips
                                        </th>
                                        <th
                                            class="py-3 px-2 bg-cyan-200 text-center"
                                        >
                                            Results
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-300">
                                    <tr
                                        v-for="(item, index) in redNinja"
                                        :key="item.id"
                                        class="text-left text-left text-[11px] sm:text-[13px] md:text-[16px]"
                                    >
                                        <td class="py-3 px-2">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.league?.title }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.fixtures }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.tip?.title }}
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <span>N/A</span>
                                            <!-- <i v-if="item.results===true" class="far fa-check-square" style="color: green;"></i>
                        <i v-if="item.results===false" class="fas fa-times" style="color: red;"></i> -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div
                                class="p-4 w-full flex flex-col items-center gap-10"
                            >
                                <div
                                    v-if="
                                        $page.props.auth.user &&
                                        $page.props.auth.user.id
                                    "
                                    class="flex flex-col lg:flex-row gap-4 flex-wrap justify-center"
                                >
                                    <a
                                        @click="
                                            reportUserClick(
                                                $page.props.auth.user.id,
                                                button.ninja,
                                                button.company,
                                            )
                                        "
                                        v-for="button in redNinjaButtons"
                                        :key="button.id"
                                        :href="button.url"
                                        :style="{
                                            backgroundColor: button.background,
                                            color: button.foreground,
                                        }"
                                        class="no-underline uppercase rounded-[30px] text-[11px] md:text-[13px] font-bold py-[4px] text-center px-[8px] sm:px-[20px]"
                                        target="_blank"
                                    >
                                        <span class="mr-1 sm:mr-2">{{
                                            button.title
                                        }}</span>
                                        |
                                        <span class="ml-1 sm:ml-2"
                                            >code: {{ button.code }}</span
                                        >
                                    </a>
                                </div>
                                <Link
                                    v-if="!$page.props.auth.user"
                                    class="no-underline bg-cyan-200 text-[black] rounded-[5px] text-[13px] font-bold text-whitesmoke py-[10px] px-[45px]"
                                    target="_blank"
                                    href="/login"
                                >
                                    LOGIN FOR THIS BETCODE
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Black Ninja -->
                <div
                    id="black-ninja"
                    class="item"
                    v-if="$page.props.auth.user && $page.props.auth.user.id"
                >
                    <div
                        class="header p-6 bg-slate-900 font-bold flex justify-between item-center cursor-pointer"
                    >
                        <div>
                            <div>BLACK NINJA</div>
                            <div>Fair Bet</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-up active-icon"></i>
                            <i class="fas fa-chevron-down inactive-icon"></i>
                        </div>
                    </div>
                    <div
                        class="content bg-slate-800 w-full transition-all duration-500"
                    >
                        <div class="mb-2">
                            <table class="shadow-2xl border md:border-2 w-full">
                                <thead class="text-black">
                                    <tr class="text-left">
                                        <th class="py-3 px-2 bg-cyan-200">#</th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            League
                                        </th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            Fixtures
                                        </th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            Tips
                                        </th>
                                        <th
                                            class="py-3 px-2 bg-cyan-200 text-center"
                                        >
                                            Results
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-300">
                                    <tr
                                        v-for="(item, index) in blackNinja"
                                        :key="item.id"
                                        class="text-left text-left text-[11px] sm:text-[13px] md:text-[16px]"
                                    >
                                        <td class="py-3 px-2">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.league?.title }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.fixtures }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.tip?.title }}
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <span>N/A</span>
                                            <!-- <i v-if="item.results===true" class="far fa-check-square" style="color: green;"></i>
                        <i v-if="item.results===false" class="fas fa-times" style="color: red;"></i> -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div
                                class="p-4 w-full flex flex-col items-center gap-10"
                            >
                                <div
                                    class="flex flex-col lg:flex-row gap-4 flex-wrap justify-center"
                                >
                                    <a
                                        @click="
                                            reportUserClick(
                                                $page.props.auth.user.id,
                                                button.ninja,
                                                button.company,
                                            )
                                        "
                                        v-for="button in blackNinaButtons"
                                        :key="button.id"
                                        :href="button.url"
                                        :style="{
                                            backgroundColor: button.background,
                                            color: button.foreground,
                                        }"
                                        class="no-underline uppercase rounded-[30px] text-[11px] md:text-[13px] font-bold py-[4px] text-center px-[8px] sm:px-[20px]"
                                        target="_blank"
                                    >
                                        <span class="mr-1 sm:mr-2">{{
                                            button.title
                                        }}</span>
                                        |
                                        <span class="ml-1 sm:ml-2"
                                            >code: {{ button.code }}</span
                                        >
                                    </a>
                                </div>
                                <!-- <Link v-if="!$page.props.auth.user" 
                    class="no-underline bg-cyan-200 text-[black] rounded-[5px] text-[13px] font-bold text-whitesmoke 
                    py-[10px] px-[45px]" target="_blank"  href="/login">
                    LOGIN FOR THIS BETCODE
                  </Link> -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- White Ninja -->
                <div
                    id="white-ninja"
                    class="item"
                    v-if="$page.props.auth.user && $page.props.auth.user.id"
                >
                    <div
                        class="header p-6 bg-slate-900 font-bold flex justify-between item-center cursor-pointer"
                    >
                        <div>
                            <div>WHITE NINJA</div>
                            <div>Assured Bet</div>
                        </div>
                        <div>
                            <i class="fas fa-chevron-up active-icon"></i>
                            <i class="fas fa-chevron-down inactive-icon"></i>
                        </div>
                    </div>
                    <div
                        class="content bg-slate-800 w-full transition-all duration-500"
                    >
                        <div class="mb-2">
                            <table class="shadow-2xl border md:border-2 w-full">
                                <thead class="text-black">
                                    <tr class="text-left">
                                        <th class="py-3 px-2 bg-cyan-200">#</th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            League
                                        </th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            Fixtures
                                        </th>
                                        <th class="py-3 px-2 bg-cyan-200">
                                            Tips
                                        </th>
                                        <th
                                            class="py-3 px-2 bg-cyan-200 text-center"
                                        >
                                            Results
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-300">
                                    <tr
                                        v-for="(item, index) in whiteNinja"
                                        :key="item.id"
                                        class="text-left text-[11px] sm:text-[13px] md:text-[16px]"
                                    >
                                        <td class="py-3 px-2">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.league?.title }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.fixtures }}
                                        </td>
                                        <td class="py-3 px-2">
                                            {{ item.tip?.title }}
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <span>N/A</span>
                                            <!-- <i v-if="item.results===true" class="far fa-check-square" style="color: green;"></i>
                        <i v-if="item.results===false" class="fas fa-times" style="color: red;"></i> -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div
                                class="p-4 w-full flex flex-col items-center gap-10"
                            >
                                <div
                                    class="flex flex-col lg:flex-row gap-4 flex-wrap justify-center"
                                >
                                    <a
                                        @click="
                                            reportUserClick(
                                                $page.props.auth.user.id,
                                                button.ninja,
                                                button.company,
                                            )
                                        "
                                        v-for="button in whiteNinjaButtons"
                                        :key="button.id"
                                        :href="button.url"
                                        :style="{
                                            backgroundColor: button.background,
                                            color: button.foreground,
                                        }"
                                        class="no-underline uppercase rounded-[30px] text-[11px] md:text-[13px] font-bold py-[4px] text-center px-[8px] sm:px-[20px]"
                                        target="_blank"
                                    >
                                        <span class="mr-1 sm:mr-2">{{
                                            button.title
                                        }}</span>
                                        |
                                        <span class="ml-1 sm:ml-2"
                                            >code: {{ button.code }}</span
                                        >
                                    </a>
                                </div>
                                <!-- <Link v-if="!$page.props.auth.user" 
                    class="no-underline bg-cyan-200 text-[black] rounded-[5px] text-[13px] font-bold text-whitesmoke py-[10px] 
                    px-[45px]" target="_blank"  href="/login">
                    LOGIN FOR THIS BETCODE
                  </Link> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Meaning of Three Ninjas Registration -->
        <Meaning />

        <!-- Analytics Section -->
        <Analytics
            :usersCount="usersCount?.users_count"
            :predictionsCount="predictionsCount"
        />

        <!-- Bottom Banner Section -->
        <section
            v-if="bottomBanner?.id"
            class="w-full my-10 lg:my-15 flex justify-center"
        >
            <a
                :href="bottomBanner?.url"
                target="_blank"
                class="w-[570px] lg:w-[970px] h-auto lg:h-[250px]"
            >
                <img
                    :src="`/banners/${bottomBanner?.filename}`"
                    alt="top-banner"
                    class="max-w-full max-h-full"
                />
            </a>
        </section>

        <!-- Footer Section -->
        <Footer />
    </div>
</template>

<style scoped>
.from-top {
    animation: fromTop 0.3s ease-in-out;
}

@keyframes fromTop {
    from {
        opacity: 0;
        transform: translateY(-60px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#center {
    height: 100%;
    width: 100%;
    margin-top: 69px;
    position: relative;
    box-shadow: 0px 5px 8px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

#center .images {
    height: 100%;
    width: 500%;
    display: flex;
}

#center .images .slide {
    width: 20%;
    transition: 1s;
}

#center .images div.bg-image {
    height: 100%;
    width: 100%;
    color: whitesmoke;
    display: flex;
    align-items: center;
    justify-content: center;
}

a.active-slide {
    transition: 0.5s;
    transform: scale(0.999);
    background: white;
    animation: animate 0.4s;
}

#slider-buttons {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
}

#slider-buttons a {
    height: 8px;
    width: 30px;
    border: 2px solid white;
    margin: 0 5px;
    border-radius: 10px;
}

@keyframes animate {
    100% {
        transform: scale(0.8);
    }
}

#slider {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

header {
    background:
        linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)),
        url(/images/bg.jpg) no-repeat scroll center center / cover;
}

.fade-in {
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.active {
    color: #fc036b;
    text-decoration: underline;
    font-weight: bold;
    text-underline-offset: 10px;
}

.item .active-icon {
    display: none;
    /* @apply hidden; */
}

.item.accordion-active .inactive-icon {
    display: none;
}

.item.accordion-active .active-icon {
    display: block;
    /* @apply: visible; */
}

.item:not(.accordion-active) .content {
    @apply h-0 overflow-hidden py-0;
}

.item .content {
    @apply p-2 h-full;
}
</style>
