@extends('laos-course.layouts.guest')

@section('content')
    <section class="header-clipping pt-10">
        <Image src={{ asset('laos-course/images/circle-accent-1.svg') }} alt="Logo" class="absolute left-0 bottom-0" />
        <div class="sunshine"></div>
        <div class="container mx-auto">
            @include('laos-course.components.guest.navbar')
            <div class="flex justify-between items-center">
                <div class="w-1/2">
                    <h1 class="text-5xl text-white mb-5">
                        Selamat Datang <span class="font-medium">Laosars</span>
                        <br />
                        Nambah <span class="font-medium">Skills</span> dari LAOS
                    </h1>

                    <p class="text-white font-light text-lg mb-8">
                        We provide tons of pathskill that you
                        <br />
                        can choose and learn
                    </p>

                    <form class="flex">
                        <input type="text"
                            class="bg-white focus:outline-none border-0 px-4 md:px-6 py-3 w-full md:w-1/2 text-[#9CADCE] rounded-tl-lg rounded-bl-lg"
                            placeholder="Your email address" />
                        <button
                            class="bg-[#ffcf3d] hover:bg-[#f8be11] transition-all duration-200 focus:outline-none shadow-inner text-white px-4 md:px-6 py-3 whitespace-no-wrap rounded-tr-lg rounded-br-lg">
                            Daftar Now
                        </button>
                    </form>
                </div>
                <div class="hidden w-1/2 md:flex justify-end pt-24 pr-16">
                    <div class="relative" style="width: 369px; height: 440px">
                        <div class="absolute border-[#ffcf3d] border-2 -mt-12 -mr-6 right-0"
                            style="width: 324px; height: 374px"></div>
                        <div class="absolute w-full h-full -mb-8 -ml-8">
                            <Image src={{ asset('laos-course/images/img-hero-mbak-alyssa-cakep.jpg') }}
                                alt="Mbak Alyssa Cakep euy" />
                        </div>
                        <div class="absolute z-10 bg-white py-3 px-4 mt-24"
                            style="transform: translateX(-50%); width: 290px">
                            <p class="text-gray-900 mb-2">
                                Metode belajar yang santai seperti nonton drakor di
                                Netflix
                            </p>
                            <span class="text-gray-600">
                                Alyssa, Apps Developer
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container px-4 mx-auto md:pt-24">
        <div class="flex justify-between items-center">
            <div class="w-auto">
                <h2 class="text-lg text-gray-600">New Classes</h2>
                <h3 class="text-xl text-gray-900">
                    Summer
                    <span class="text-[#67da98]"> Productive</span>
                </h3>
            </div>
            <div class="w-auto">
                <Link href="/courses" class="text-gray-600 hover:underline text-sm">
                View All Course
                </Link>
            </div>
        </div>
        <div class="flex flex-wrap justify-start items-center -mx-4 mt-6">
            {{-- {/* <div class="w-full text-center-py-12 flex justify-center text-semibold my-3">No Item Found</div> */}
            {data?.length > 0 ? (
                data?.map((item, index) => {
                    return (
                        <RenderCourse
                            key={index}
                            item={item}
                        ></RenderCourse>
                    );
                })
            ) : (
                <div class="w-full text-center-py-12 flex justify-center text-semibold my-3">
                    No Item Found
                </div>
            )} --}}
            @forelse ($courses as $course)
                <div class="w-1/4 px-4">
                    <div class="item relative">
                        <figure class="item-image">
                            <Image src={{ asset('laos-course/images/icon-play.svg') }} alt="" class="icon-play" />
                            <img src={{ $course->getFirstMediaUrl('course-thumbnail') }}
                                alt="Course {{ $course->title }}" />
                        </figure>
                        <div class="item-meta mt-2">
                            <h4 class="text-lg text-gray-900">
                                {{ $course->title }}
                            </h4>
                            <h5 class="text-sm text-gray-600">
                                {{ ucfirst($course->level) }}
                            </h5>
                        </div>
                        <a href="{{ route('laos-course.frontpage.show', $course->slug) }}" class="link-wrapped"></a>
                    </div>
                </div>
            @empty
                <div class="w-full text-center-py-12 flex justify-center text-semibold my-3">
                    No Item Found
                </div>
            @endforelse
        </div>
    </section>
    <section class="container px-4 mx-auto md:pt-24" id="category">
        <div class="flex justify-between items-center">
            <div class="w-auto">
                <h2 class="text-lg text-gray-600">Category</h2>
                <h3 class="text-xl text-gray-900">
                    Explore & <span class="text-[#67da98]">Learn</span>
                </h3>
            </div>
        </div>
        <div class="flex flex-wrap justify-start items-center -mx-4 mt-6">
            {{-- {data?.length > 0 ? (
                data?.map((item, index) => {
                    return (
                        <RenderCategory
                            item={item}
                            key={index}
                        ></RenderCategory>
                    );
                })
            ) : (
                <div class="w-full text-center-py-12">
                    No Item Found
                </div>
            )} --}}
            @forelse ($categories as $category)
                <div class="w-1/6 px-4 h-[200px]">
                    <div class="card relative transition-all duration-300">
                        <img src="{{ $category->getFirstMediaUrl('course-category-image') }}" width="30px" height="30px"
                            class="svg-fill" />
                        <div class="card-meta mt-10">
                            <h4 class="text-lg text-gray-900 transition-all duration-200 w-1/2">
                                {{ $category->name }}
                            </h4>
                            <h5 class="text-sm text-gray-600 transition-all mt-2 duration-200">
                                {{ $category->courses_count }} Courses
                            </h5>
                        </div>
                        <a href="#" class="link-wrapped">
                        </a>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </section>
    <section class="mt-24 -mb-10 bg-[#2DCC70] py-12">
        @include('laos-course.components.guest.footer')
    </section>
@endsection
