<script setup>
import { ref } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import NavBar from "@/Components/NavBar.vue";
import Footer from "@/Components/Footer.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "../axiosConfig";
import InputError from "@/Components/InputError.vue";

const displayMessage = (message, type) =>
    toast(message, { autoClose: 1000, type });

const form = useForm({
    name: "",
    email: "",
    subject: "",
    message: "",
});

const { url } = usePage();
const stateUrl = ref(null);
stateUrl.value = url;
const errors = ref(null);

const submitData = async () => {
    if (stateUrl.value.includes("contact")) {
        try {
            const response = await axios.post("contact-us", form);
            if (response.status === 201) {
                displayMessage(response.data.message, "success");
                form.reset();
            }
        } catch (error) {
            errors.value = error.response.data.errors;
            displayMessage(error.response.data.message, "error");
        }
    }
};
</script>

<template>
    <Head title="Contact Us" />

    <div
        class="h-screen w-screen overflow-x-hidden bg-[#EEF5FF] scroll-smooth fade-in"
    >
        <!-- Navbar section -->
        <NavBar />

        <section class="mt-[70px] lg:mt-[77.5px] w-full from-back">
            <div
                id="border"
                class="flex flex-col items-center w-[98%] lg:w-[90%] mx-auto bg-[#101010] mt-[7rem] mb-[6rem]"
            >
                <article
                    class="my-10 text-center flex flex-col items-center text-white w-full px-5 z-10"
                >
                    <h2 class="text-[42px]">
                        Get In <span class="text-[#fc036b]">Touch</span>
                    </h2>
                    <p class="w-[98%] lg:w-[80%] text-[18px]">
                        Our mission is to offer you the best odds for maximizing
                        your betting experience and winnings. Whether you're a
                        cautious player or a high-risk taker, our 3 Ninjas have
                        diverse winning options tailored just for you.
                    </p>
                </article>
                <article
                    class="mb-12 flex flex-col lg:flex-row justify-center p-2 lg:p-5 gap-7 w-full z-10"
                >
                    <div class="w-full h-[650px] bg-[#3c3c3c] px-2 lg:px-5">
                        <form
                            action=""
                            @submit.prevent="submitData"
                            class="flex flex-col gap-4 text-white h-full"
                        >
                            <h2
                                class="text-[#fc036b] text-[18px] font-extrabold text-center mt-5"
                            >
                                Leave us a Message
                            </h2>
                            <label class="block" for="name"
                                ><span class="ml-2 text-[14px]">Your name</span>
                                <input
                                    id="name"
                                    class="block w-full rounded-[7px] p-[12px] border-0 outline-0 focus:ring-0 bg-[#191919]"
                                    type="text"
                                    placeholder="Your Name"
                                    v-model="form.name"
                                />
                                <InputError
                                    v-if="errors && errors.name"
                                    class="text-center"
                                    :message="errors.name[0]"
                                />
                            </label>

                            <label class="block" for="email"
                                ><span class="ml-2 text-[14px]">Email</span>
                                <input
                                    id="email"
                                    class="block w-full rounded-[7px] p-[12px] border-0 outline-0 focus:ring-0 bg-[#191919]"
                                    type="email"
                                    placeholder="Email"
                                    v-model="form.email"
                                />
                                <InputError
                                    v-if="errors && errors.email"
                                    class="text-center"
                                    :message="errors.email[0]"
                                />
                            </label>

                            <label class="block" for="subject"
                                ><span class="ml-2 text-[14px]">Subject</span>
                                <input
                                    id="subject"
                                    class="block w-full rounded-[7px] p-[12px] border-0 outline-0 focus:ring-0 bg-[#191919]"
                                    type="text"
                                    placeholder="Write a Subject"
                                    v-model="form.subject"
                                />
                                <InputError
                                    v-if="errors && errors.subject"
                                    class="text-center"
                                    :message="errors.subject[0]"
                                />
                            </label>

                            <label class="block" for="message"
                                ><span class="ml-2 text-[14px]">Message</span>
                                <textarea
                                    id="message"
                                    class="block w-full rounded-[7px] p-[12px] border-0 outline-0 focus:ring-0 bg-[#191919] resize-none h-[150px]"
                                    cols="30"
                                    rows="10"
                                    placeholder="Your Message"
                                    v-model="form.message"
                                ></textarea>
                                <InputError
                                    v-if="errors && errors.message"
                                    class="text-center"
                                    :message="errors.message[0]"
                                />
                            </label>

                            <button
                                class="rounded-[7px] p-[20px] border-0 outline-0 bg-[#c9145f] hover:bg-[#fc036b] transition-colors duration-300 py-2 uppercase"
                                type="submit"
                            >
                                Submit
                            </button>
                        </form>
                    </div>
                    <div class="w-full h-[650px]">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.0619192417603!2d-0.18495832524169026!3d5.557840894422605!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf907ed7325673%3A0xa67c85656e770836!2sOxford%20St%2C%20Accra!5e0!3m2!1sen!2sgh!4v1716589613279!5m2!1sen!2sgh"
                            width="100%"
                            height="650"
                            style="border: 0"
                            allowfullscreen="false"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        >
                        </iframe>
                    </div>
                </article>
            </div>
        </section>

        <!-- Footer section -->
        <Footer />
    </div>
</template>

<style scoped>
#border {
    position: relative;
    background: repeating-conic-gradient(
        from var(--a),
        #fc036b 0%,
        #fc036b 10%,
        transparent 10%,
        transparent 50%
    );
    animation: animate 6s linear infinite;
    padding: 60px;
}
#border::before {
    content: "";
    position: absolute;
    inset: 0;
    background: repeating-conic-gradient(
        from var(--a),
        #fc036b 0%,
        #fc036b 10%,
        transparent 10%,
        transparent 50%
    );
    animation: animate 6s linear infinite;
    animation-delay: -1.5s;
}

#border::after {
    content: "";
    position: absolute;
    inset: 4px;
    border: 20px solid #eef5ff;
    background: #101010;
}

@media screen and (max-width: 767px) {
    #border {
        padding: 0;
    }

    #border::after {
        inset: 2px;
        border: 5px solid #eef5ff;
    }
}

@property --a {
    syntax: "<angle>";
    inherits: false;
    initial-value: 0deg;
}

@keyframes animate {
    0% {
        --a: 0deg;
    }
    100% {
        --a: 360deg;
    }
}

.from-back {
    animation: fromBack 0.3s ease-in-out;
}

@keyframes fromBack {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
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
</style>

/* laravel 11 how to send email to an email address joshuagato37@gmail.com from
a contact form with the following details email, name, subject, message */
