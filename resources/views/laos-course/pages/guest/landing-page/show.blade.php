@extends('laos-course.layouts.guest')

@section('content')
    <section class="pt-10 relative overflow-hidden" style="height: 660px">
        <div class="video-wrapper">
            {{-- <Youtube
                        videoId={
                            props.course?.chapters?.[0]?.lessons?.[0].youtube_id
                        }
                        id={
                            props.course?.chapters?.[0]?.lessons?.[0].youtube_id
                        }
                        opts={{
                            playerVars: {
                                loop: 1,
                                mute: 1,
                                autoplay: 1,
                                controls: 0,
                                showinfo: 0,
                            },
                        }}
                        onEnd={(e) => e.target.playVideo()}
                    /> --}}
        </div>
        <div class="absolute inset-0 z-0 w-full h-full bg-black opacity-75"></div>
        <div class="meta-title absolute inset-0 object-fill z-0 w-full flex justify-center items-center">
            <div class="text-center">
                <h3 class="text-lg text-white">Kelas Online: </h3>
                <h4 class="text-6xl text-[#2dcc70] font-semibold">
                    {{ strtoupper($course->title) }}
                </h4>
            </div>
        </div>
        <div class="container mx-auto z-10 relative">
            @include('laos-course.components.guest.navbar')
        </div>
    </section>

    <section class="container mx-auto pt-24 relative">
        <div class="absolute top-0 w-full transform -translate-y-1/2">
            <div class="w-3/4 mx-auto">
                <div class="flex justify-between">
                    <div class="border border-gray-300 bg-white p-6 w-full md:w-1/3" style="width: 290px">
                        <div class="flex">
                            <div class="w-auto">
                                <img src="{{ asset('laos-course/images/icon-nametag.svg') }}" height="41"
                                    width="42" />
                            </div>
                            <div class="ml-5">
                                <span class="text-gray-600 block">Student</span>
                                <span class="text-gray-900 text-3xl">{{ $course->my_courses_count }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-300 bg-white p-6 w-full md:w-1/3" style="width: 290px">
                        <div class="flex">
                            <div class="w-auto">
                                <img src="{{ asset('laos-course/images/icon-playback.svg') }}" height="41"
                                    width="42" />
                            </div>
                            <div class="ml-5">
                                <span class="text-gray-600 block">Chapter</span>
                                <span class="text-gray-900 text-3xl">{{ $course->course_chapters_count }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-300 bg-white p-6 w-full md:w-1/3" style="width: 290px">
                        <div class="flex">
                            <div class="w-auto">
                                <img src="{{ asset('laos-course/images/icon-certificate.svg') }}" height="41"
                                    width="42" />
                            </div>
                            <div class="ml-5">
                                <span class="text-gray-600 block">Level</span>
                                <span class="text-gray-900 text-3xl">{{ ucfirst($course->level) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-3/4 mx-auto mt-8">
            <div class="w-full">
                <section>
                    <h6 class="font-medium text-gray-900 text-2xl mb-4">
                        About
                        <span class="text-[#2DCC70]"> Course</span>
                    </h6>
                    <div class="text-gray-600 text-lg leading-relaxed mb-3">
                        <span>{!! $course->content !!}</span>
                    </div>
                </section>

                <section class="mt-10">
                    <h6 class="font-medium text-gray-900 text-2xl mb-4">
                        Course
                        <span class="text-[#2dcc70]"> Photos</span>
                    </h6>
                    <div class="flex flex-wrap justify-start items-center -mx-4 mt-6">
                        {{-- {galleries.length > 0 ? (
                        galleries.map((photo, index) => (
                        <ListCoursePhoto data={photo.original_url} key={index} />
                        ))
                        ) : (
                        <div class="w-full text-center py-12">
                            No Item Found
                        </div>
                        )} --}}
                        @forelse ($course->getMedia('course-gallery') as $index => $photo)
                            <div class="w-full md:w-[200px] px-4 mb-4 md:mb-0 mt-3">
                                <div class="item relative">
                                    <figure class="item-image cursor-pointer"
                                        onclick="openGalleryModal(`{{ $photo->original_url }}`)">
                                        <img src="{{ asset('laos-course/images/icon-preview.svg') }}" id="svg-preview" />
                                        <img src="{{ $photo->original_url }}" alt="Photo"
                                            class="object-cover h-40 md:h-32 w-full">
                                    </figure>
                                </div>
                            </div>
                        @empty
                            <div class="w-full text-center py-12">
                                No Item Found
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="mt-10">
                    <h6 class="font-medium text-gray-900 text-2xl mb-4">
                        You Will <span class="text-[#2dcc70]">Learn</span>
                    </h6>

                    <div class="accordion">
                        @forelse ($course->courseChapters as $chapter)
                            <div class="bg-white border border-gray-300 overflow-hidden">
                                <div class="meta pl-4 py-2 relative flex justify-between items-center">
                                    <span class="text-gray-600">{{ $chapter->title }}</span>
                                    <button class="link-wrapped pr-5 focus:outline-none"
                                        onclick="toggleAccordion({{ $chapter->id }})">
                                        @if ($chapter->courseLessons)
                                            <img src="{{ asset('laos-course/images/icon-arrow-down.svg') }}"
                                                id="arrow-{{ $chapter->id }}"
                                                class="transition-all duration-300 transform rotate-0" alt="arrow-icon">
                                        @endif
                                    </button>
                                </div>
                                <div id="accordion-{{ $chapter->id }}"
                                    class="accordion-content transition-all duration-500"
                                    style="height: 0; overflow: hidden;">
                                    <div class="py-2 bg-gray-100">
                                        @foreach ($chapter->courseLessons as $lesson)
                                            <div
                                                class="relative hover:bg-gray-200 flex justify-between items-center pl-8 pr-4 py-2">
                                                <span class="text-gray-600">{{ $lesson->title ?? 'Course name' }}</span>

                                                @if (!$lesson->is_locked)
                                                    <button onclick="openVideoModal('{{ $lesson->youtube_id }}')"
                                                        class="link-wrapped"></button>
                                                    <img src="{{ asset('laos-course/images/icon-play.svg') }}"
                                                        width="24" height="24" alt="play-icon" class="fill-teal-500">
                                                @else
                                                    <img src="{{ asset('laos-course/images/icon-lock.svg') }}"
                                                        width="24" height="24" alt="lock-icon">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-full text-center py-12">
                                No Chapter Found
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="mt-10 w-full md:w-2/3">
                    <h6 class="font-medium text-gray-900 text-2xl mb-4">
                        Our
                        <span class="text-[#2dcc70]">
                            Instructor
                        </span>
                    </h6>
                    <div class="flex items-center">
                        @if ($course->mentor->avatar_url)
                            <img src="{{ $course->mentor->avatar_url }}" alt={{ $course->mentor->name }}
                                class="w-14 h-14 rounded-full overflow-hidden object-cover" />
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ $course->mentor->name }}"
                                alt={{ $course->mentor->name }}
                                class="w-14 h-14 rounded-full overflow-hidden object-cover" />
                        @endif
                        <div class="ml-4">
                            <h2 class="text-lg text-gray-900">
                                {{ ucfirst($course->mentor->name) }}
                            </h2>
                            <h3 class="text-sm text-gray-60">
                                {{ $course->mentor->custom_fields->occupation ?? 'Instructor' }}
                            </h3>
                        </div>
                    </div>
                </section>

                <section class="mt-10 w-full md:w-6/12">
                    <h6 class="font-medium text-gray-900 text-2xl mb-4">
                        Happy <span class="text-teal-500">Students</span>
                    </h6>

                    @forelse ($course->courseReviews as $review)
                        <div class="mt-8">
                            <!-- Bintang Rating -->
                            @php $rating = $review->rating ?? 0; @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <img src="{{ $i <= $rating ? asset('laos-course/images/icon-star.svg') : asset('laos-course/images/icon-star-off.svg') }}"
                                    alt="{{ $i <= $rating ? 'Full star' : 'Empty star' }}" class="inline-block"
                                    width="26" height="26" />
                            @endfor

                            <!-- Catatan Ulasan -->
                            <p class="text-gray-600 mt-1">
                                {{ $review->note ?? "Student's response" }}
                            </p>

                            <!-- Profil Pengguna -->
                            <div class="flex items-center mt-4">
                                <div class="rounded-full overflow-hidden">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'student') }}"
                                        alt="{{ $review->user->name ?? "student's name" }}"
                                        class="object-cover w-14 h-14" />
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-lg text-gray-900">
                                        {{ $review->user->name ?? "Student's name" }}
                                    </h2>
                                    <h3 class="text-sm text-gray-600">
                                        {{ $review->user->custom_fields['occupation'] ?? "Student's role" }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full flex justify-center text-center py-12">
                            No Reviews Found
                        </div>
                    @endforelse
                </section>
            </div>
        </div>
    </section>

    <section class="mt-24 -mb-10 bg-[#2DCC70] py-12" ref={footer}>
        @include('laos-course.components.guest.footer')
    </section>

    {{-- Modal --}}
    <dialog id="modal_course" class="modal">
        <div class="modal-box w-[100%] h-[60%]">
            {{-- Can be Image or Youtube Iframe --}}
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    @push('scripts')
        <script>
            const videoWrapper = $('.video-wrapper')[0];
            const videoId = '{{ $course->courseChapters[0]->courseLessons[0]->youtube_id }}';
            const video = document.createElement('iframe');
            video.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1&loop=1&controls=1&showinfo=0`;
            video.setAttribute('allow',
                'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
            video.setAttribute('allowfullscreen', '');
            video.setAttribute('frameborder', '0');
            video.setAttribute('width', '100%');
            video.setAttribute('height', '100%');
            videoWrapper.appendChild(video);

            function openGalleryModal(photoUrl) {
                modal_course.showModal();
                // create img element contains photo
                const img = document.createElement('img');
                img.src = photoUrl;
                img.setAttribute('class', 'object-cover h-full w-full');
                // append img to modal-box
                const modalBox = document.querySelector('.modal-box');
                modalBox.innerHTML = '';
                modalBox.appendChild(img);
            }

            function openVideoModal(youtubeId) {
                modal_course.showModal();
                // create iframe element contains youtube video
                const iframe = document.createElement('iframe');
                iframe.src = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&controls=0&showinfo=0&fs=1`;
                iframe.setAttribute('allow',
                    'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
                iframe.setAttribute('allowfullscreen', '');
                iframe.setAttribute('frameborder', '0');
                iframe.setAttribute('width', '100%');
                iframe.setAttribute('height', '100%');
                // append iframe to modal-box
                const modalBox = document.querySelector('.modal-box');
                modalBox.innerHTML = '';
                modalBox.appendChild(iframe);
            }

            // if modal is closed, remove the content inside modal-box
            modal_course.addEventListener('close', () => {
                const modalBox = document.querySelector('.modal-box');
                modalBox.innerHTML = '';
            });

            function toggleAccordion(id) {
                const content = document.getElementById(`accordion-${id}`);
                const arrow = document.getElementById(`arrow-${id}`);
                if (content.style.height === '0px' || content.style.height === '') {
                    content.style.height = `${content.scrollHeight}px`;
                    arrow.classList.add('rotate-180');
                } else {
                    content.style.height = '0';
                    arrow.classList.remove('rotate-180');
                }
            }
        </script>
    @endpush
@endsection
