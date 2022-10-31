<x-app-layout>
    <div class="flex flex-col justify-center items-center gap-4 h-1/2-screen">
        <x-application-logo class="block h-40 w-auto" />
        <p class="text-center text-lg w-3/4 pt-5 border-t-4 border-secondary-fg">
            Looking for elegant yet affordable venue. Perfect venue for Corporate events and private occasions.
            Express the youthful soul inside you and enjoy the amazing treats that villa capco prepare for you.
        </p>
    </div>

    <article class="mx-20 my-5 bg-primary-bg border rounded-lg overflow-hidden">
        <img src="{{ asset('storage/images/accommodations/pool_1.jpg') }}" alt="First Pool" class="float-left object-cover w-[33.3333vw] h-auto mr-5">
        <div class="mr-5 mt-3">
            <h1 class="text-start text-2xl w-3/4 text-primary-fg font-bold pt-5">
                The first Pool of Villa Capco
            </h1>
            <p class="text-start text-lg">
                We started just like how any resorts did. We start from one pool until we reach where we are now.
                The place was just right; not too far from the street to find and navigate but not too close so that 
                visitors can still feel the freedom away from the busy city. Our first pool is our constant reminder
                of our big vision with even greater passion. Where we are now started from that tiny little pool.
            </p>
        </div>
    </article>

    <article class="mx-20 my-10 bg-primary-bg border rounded-lg overflow-hidden">
        <img src="{{ asset('storage/images/addons/function_hall.jpg') }}" alt="Function Hall" class="float-right object-cover w-[33.3333vw] h-auto ml-5">
        <div class="ml-5 mt-5">
            <h1 class="text-end text-2xl w-3/4 text-primary-fg font-bold pt-5">
                Our undying commitment
            </h1>
            <p class="text-end text-lg">
                We believe that everyone has the right for affordable place to celebrate. Villa Capco caters those
                people who would want the best of two worlds. Being gorgeous at the right expense. We commited our
                passion and love for giving services to the people and making their moments memorable. We have the
                right accommodations for you!
            </p>
        </div>
    </article>

    <div class="flex flex-col justify-center items-center mt-20">
        <h1 class="text-center text-3xl w-3/4 font-bold border-b-2 pb-5 text-secondary-fg border-secondary-fg">
            What do People thinks about our Service?
        </h1>
    </div>

    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 justify-around m-10 gap-10">
        @foreach ($ratings as $rating)
            <article class="bg-primary-bg border rounded-lg transition duration-700 ease-in-out hover:scale-105 overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center">
                        <x-ratings :value="$rating->rating_score" />
                        <x-card-title :value="number_format($rating->rating_score, 1)" class="inline font-extrabold ml-3 pt-0.5 text-primary-fg" />
                    </div>
                    <x-tag :value="'Written by: ' . $rating->user->getFullname()" class="bg-secondary-bg" />
                    <x-card-description :value="$rating->comment" />
                </div>
            </article>
        @endforeach
    </div>    
    <div class="flex justify-center items-center mt-20">
        <h1 class="mb-8 text-center text-3xl w-3/4 font-bold border-b-2 pb-5 text-secondary-fg border-secondary-fg">
            Company Profile
        </h1>
    </div>
    <div class="container mx-auto">
        <div class="mb-10 px-8 lg:flex lg:justify-center lg:space-x-8">
            <iframe class="w-full" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15447.507150333346!2d121.0877006!3d14.5490378!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf6fe8b234f443b32!2sVilla%20Capco!5e0!3m2!1sen!2sph!4v1665798708542!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <p class="mt-5 text-lg lg:w-1/2 lg:mt-0 lg:text-2xl">
                Villa Capco opened early 2019 and is a family owned business. Villa capco’s goal is to provide affordable yet quality service to our clients. We ensure that the safety and the comfort of our clients are on top of the list. Our rate is notably reasonable for a metropolitan location.
            </p>
        </div>
    </div>
    <div class="flex flex-col justify-center items-center mt-20">
        <h1 class="text-center text-3xl w-3/4 font-bold border-b-2 pb-5 text-secondary-fg border-secondary-fg">
            Newsletter
        </h1>
    </div>
    <div class="p-6 container md:w-2/3 xl:w-auto mx-auto flex flex-col xl:items-stretch justify-between xl:flex-row">
        <div class="xl:w-1/2 md:mb-14 xl:mb-0 relative h-auto flex items-center justify-center">
            <img src="{{ asset('storage/images/newsletter.svg') }}" alt="Envelope with a newsletter" role="img" class="h-full xl:w-full lg:w-1/2 w-full" />
        </div>
        <div class="w-full xl:w-1/2 xl:pl-40 xl:py-28">
            <h1 class="text-2xl md:text-4xl xl:text-5xl font-bold leading-10 text-gray-800 mb-4 text-center xl:text-left md:mt-0 mt-4">Subscribe</h1>
            <p class="text-base leading-normal text-gray-600 text-center xl:text-left">Get the latest updates and promos every month in your inbox.</p>
            <div class="mt-12">
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex items-stretch">
                    @csrf
                    <input name="email" class="bg-gray-300 rounded-lg rounded-r-none text-base leading-none text-gray-800 p-5 w-4/5 border border-transparent focus:outline-none focus:border-secondary-bg" type="email" placeholder="Your Email" required/>
                    <button class="w-32 rounded-l-none hover:bg-primary-fg bg-secondary-bg rounded text-base font-medium leading-none text-white p-5 uppercase focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-bg">subscribe</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>