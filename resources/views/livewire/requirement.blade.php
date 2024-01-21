<!-- Post Your Requirements Section -->
<section class="text-gray-600 body-font">
    <div class="container mx-auto flex px-4 lg:px-12 py-12 md:py-24 items-center justify-center flex-col">
        <div class="text-center lg:w-2/3 w-full">
            <h1 class="title-font text-base md:text-3xl mb-4 font-medium text-gray-900">Explore Endless Business Opportunities</h1>
            <p class="mb-8 leading-relaxed">Navigating the vast landscape of business opportunities can be challenging. Whether you're seeking suppliers, service providers, or business insights, we're here to guide you. Share your requirements, and let us connect you to the resources that propel your business forward.</p>
            <div class="flex justify-center">
                <button x-on:click="$dispatch('open-modal', { id: 'Requirement' })" class="inline-flex text-white bg-purple-500 border-0 py-2 px-6 focus:outline-none hover:bg-purple-600 rounded-full text-base md:text-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out">
                    Post Your Business Requirements
                </button>
            </div>
        </div>
    </div>
</section>
