@extends('layouts.app')

@section('title', 'Trang chủ')

@section('breadcrumb', 'Khóa học')

@section('content')
    <main class="max-w-7xl mx-auto px-6 lg:px-20 py-10">
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Sidebar Filter -->
            <aside class="w-full lg:w-64 flex-shrink-0 space-y-8">
                <!-- HSK Level Filter -->
                <div
                    class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-sm border border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2 mb-4 text-slate-900 dark:text-white font-semibold">
                        <span class="material-symbols-outlined text-primary">school</span>
                        <h3>HSK Levels</h3>
                    </div>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary" type="checkbox" />
                            <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-primary">HSK 1 -
                                Beginner</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary"
                                type="checkbox" />
                            <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-primary">HSK 2 -
                                Elementary</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input checked="" class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary"
                                type="checkbox" />
                            <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-primary">HSK 3 -
                                Intermediate</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary"
                                type="checkbox" />
                            <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-primary">HSK 4 - Upper
                                Int.</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary"
                                type="checkbox" />
                            <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-primary">HSK 5 -
                                Advanced</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input class="w-5 h-5 rounded border-slate-300 text-primary focus:ring-primary"
                                type="checkbox" />
                            <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-primary">HSK 6 -
                                Mastery</span>
                        </label>
                    </div>
                </div>
                <!-- Price Range Filter -->
                <div
                    class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-sm border border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2 mb-4 text-slate-900 dark:text-white font-semibold">
                        <span class="material-symbols-outlined text-primary">payments</span>
                        <h3>Price Range</h3>
                    </div>
                    <input class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-primary"
                        type="range" />
                    <div class="flex justify-between mt-2 text-xs text-slate-500">
                        <span>$0</span>
                        <span>$500</span>
                    </div>
                </div>
                <!-- Format Filter -->
                <div
                    class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-sm border border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2 mb-4 text-slate-900 dark:text-white font-semibold">
                        <span class="material-symbols-outlined text-primary">videocam</span>
                        <h3>Course Format</h3>
                    </div>
                    <div class="flex flex-col gap-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input checked="" class="w-5 h-5 border-slate-300 text-primary focus:ring-primary"
                                name="format" type="radio" />
                            <span class="text-sm text-slate-600 dark:text-slate-400">Online Live</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input class="w-5 h-5 border-slate-300 text-primary focus:ring-primary" name="format"
                                type="radio" />
                            <span class="text-sm text-slate-600 dark:text-slate-400">Recorded Classes</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input class="w-5 h-5 border-slate-300 text-primary focus:ring-primary" name="format"
                                type="radio" />
                            <span class="text-sm text-slate-600 dark:text-slate-400">Offline Campus</span>
                        </label>
                    </div>
                </div>
            </aside>
            <!-- Course Grid -->
            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <!-- Course Card 1 -->
                <div
                    class="group bg-white dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 overflow-hidden">
                        <img alt="HSK 3 Comprehensive Course"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            data-alt="Modern Chinese classroom with textbooks and tea"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuACxjR27gsJ2CEcUTkMgvZEEVf6EQNbY9tP8C7zlAQoP6vyvuM2Z-VVfooHP572h_jmvT3KWaXA5oljGrI5ITJVQWsx-VIYlUjWOgVqAh2ONeuZEXXtYU4XvnFkD35y2JYgYqhyd4A2CMw6k61LaHQT8j0ljjMYKG0F67LyQpgwQI5NvW2UMSs0YtoUHumsmNQfzZpRln5K8V33AMP2zMoZCxfdCAmII35UCNAlRhtnUwDO_Hy9AxYXJTo4zSHDfCyqU9oZHWha_g" />
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-primary/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">HSK
                                3</span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3
                            class="font-poppins text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-primary transition-colors">
                            Intermediate Chinese Mastery: HSK 3</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Perfect your grammar and double your vocabulary
                            with native experts.</p>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                <img alt="Instructor" data-alt="Professional female Asian instructor portrait"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAAw_HvDsen54DNJYg--jV4XnteTgKJYN4I5cxZQap1_gSORLC0WQR1Y7fJ93vdTnUIAGVQDWlJK6jC--h9qbF9m7aASYThMbr37td_O3KpX6SdKtWCUtvREG3elZoPwcJKSoLqp-MjYevKN05XJulyUCIVoajWE9oTcbb6S4UdwB1GhCbok1mIiydnsTIQb1rvhDn-ivfwhmbR77JzcMCryVL7e-HdWa5MqshNryhfzkdvCiNmP_VuCTlQELrurV9RsmHN6hbd8Q" />
                            </div>
                            <div class="text-xs">
                                <p class="font-semibold text-slate-900 dark:text-slate-200">Prof. Li Wei</p>
                                <p class="text-slate-500">48 Lessons</p>
                            </div>
                        </div>
                        <div
                            class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-xl font-bold text-slate-900 dark:text-white">$129.00</span>
                            <button
                                class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                                Đăng ký học
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Course Card 2 -->
                <div
                    class="group bg-white dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 overflow-hidden">
                        <img alt="Chinese Business Culture"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            data-alt="Traditional Chinese calligraphy tools on desk"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCRnO-60OIVxlyGOWP-k1phAACchqsCgPaXMWsIoKqnsrNAdnEj48pW_rN0LJJtzqYsH_jeUVqzIHO_GdkpeyiOyoJUiX66U44CG6nzEYisl-thQw9NmxmfiitD4PFckD1ddO21keBPIlIEbSjEHcGE8kV4wLs8PRrWqjr4LiMsdIQAGZ2-BVic-M9qYZJpw6Y97fBd6Z6m5EsVjHNJvu5S6RugUVCBGk2lO2jWeGGtBlPoyPiXke5c9z7FR37hXp4vrSQLeO6yZQ" />
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-primary/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">HSK
                                4</span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3
                            class="font-poppins text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-primary transition-colors">
                            Business Mandarin for Professionals</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Navigate corporate China with confidence and
                            professional etiquette.</p>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                <img alt="Instructor" data-alt="Professional male Asian instructor portrait"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuATd2vObhv8inzIHez5TOldUuBiSQYho7hQ1YtfDSGoOGRLOPEI-58oyzZsV0Bc9aYropaobl-bpn2mI7arsjtDhsXeRaS_OKpk7OOr9D6mJBDQxUGWtFkIHohOCcS5brjIx7e5gWxI4V6-13g84aA8IB8ifs-Lh010_uhT0Jbvnzz502mST6w9sPyLRpWkEsJPt3rrYZDJEQ5NAm6ycgoebgwDUDbLPvIF_os5SfyQ4h5NIdwuJhWgLpdXi5M626rxCvKnx5PTmA" />
                            </div>
                            <div class="text-xs">
                                <p class="font-semibold text-slate-900 dark:text-slate-200">Dr. Zhang Hao</p>
                                <p class="text-slate-500">60 Lessons</p>
                            </div>
                        </div>
                        <div
                            class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-xl font-bold text-slate-900 dark:text-white">$199.00</span>
                            <button
                                class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                                Đăng ký học
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Course Card 3 -->
                <div
                    class="group bg-white dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 overflow-hidden">
                        <img alt="Beginner Chinese"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            data-alt="Colorful abstract Chinese character art"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDqgqvrzg33EaZjMMlT0J8dYoLcmih4N5SqzOrOkEd4pz6sDRSQjQfdm9jkYuhZuhySFu1-z4IC0As9Y7ymv2mP7Vt7seDTPk2EGakcXUNh3BDYs_8jAVIvPGnTFFILYTnvJNDcKqByOy8z-C4HdXEeni8PHRrdldmm5sExeCla38Fn8F6iAzwifUs5AsF_foobF-MrOgl5fx0n8nni5UCkopHY0DtBlKTwheDuw4dsUlqsivpifFAJICywEeO6GDTBTvdc-bHFZQ" />
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-primary/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">HSK
                                1</span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3
                            class="font-poppins text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-primary transition-colors">
                            Pinyin and Tones Foundation</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Start your journey with a solid foundation in
                            pronunciation and basic characters.</p>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                <img alt="Instructor" data-alt="Friendly female Asian tutor smiling"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBpvmHaUHK4NsxneWw1p-SuomgExDrq3ZHcN-j3qki9t3YLkCff0NAWaSQ8vuD8-uvsgTYnutIdB2zRA1Kz5vIullw119dmYuyF8PWjZ8ZW4keSgv2vxJ5uN9gaMFiF_m9opk0InynsLLe_TUTHPDj11_xZkEdB_cBiRWu9c7MTy9-iQFiDhtWSDijBDYHv3p2CVP2dgCADDrESXIh5QnmAttzF6nwRb8PdjLavvjzR9hOQehieEriddMX23t3QHUM9p4XK6UG5cg" />
                            </div>
                            <div class="text-xs">
                                <p class="font-semibold text-slate-900 dark:text-slate-200">Ms. Chen Yan</p>
                                <p class="text-slate-500">24 Lessons</p>
                            </div>
                        </div>
                        <div
                            class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-xl font-bold text-slate-900 dark:text-white">$79.00</span>
                            <button
                                class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                                Đăng ký học
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Course Card 4 -->
                <div
                    class="group bg-white dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 overflow-hidden">
                        <img alt="Advanced HSK 6"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            data-alt="Beautiful landscape of Great Wall of China"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXkvTl5kToLfjyP6bkwm-Q1S22nj5_Y5GBK0l8OLCGOXeFTJ9tVzTN1Wk8vgH4Isit0KWOf3AFNJORJQkItq88BEHW46VXxr2XVWytbWNvJMhwv_iewVInZEftJNwdnnvSY62Y6lx_vYwOK25lZhaBRLuWVRax4pV17Mw82xIESnd8Xpz6Fv0AiHNM_Rajr6_XRrd0ooGYdX9-ZTxK0hEGAT3UjgKqIdU4yI7XIJVPj2D1C8-8mnGkKNmFm55nComK4n2JSNNdrw" />
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-primary/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">HSK
                                6</span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3
                            class="font-poppins text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-primary transition-colors">
                            Chinese Literature &amp; Philosophy</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Analyze classical texts and modern literature
                            to reach native-level fluency.</p>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                <img alt="Instructor" data-alt="Senior male Asian scholar portrait"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCi47oudgx2xKaV4GHXOVSTlXBvwxeqQp7UaJFEwd358uUWCkyrCoiLeeAzvNOc7KLWXnsRcGlBmLhVT0hUd17b5ePvOFM35_zK4H18HjhhT1chZyiGq6tiKbjbaxOjPAq7KcFX0fIlLuqvO8o9VE-Fdnr4drOChAEpikVfSh7b6a_zblMUUBw6VMXriiJCDmrcLHMGJw3npCvvd5H_z4u4nc-UIJvr6IFJ4GtoFrsSrU5-OleFksdvKz8pqSBgtJqgEV8e-WSngQ" />
                            </div>
                            <div class="text-xs">
                                <p class="font-semibold text-slate-900 dark:text-slate-200">Prof. Wang Feng</p>
                                <p class="text-slate-500">72 Lessons</p>
                            </div>
                        </div>
                        <div
                            class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-xl font-bold text-slate-900 dark:text-white">$249.00</span>
                            <button
                                class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                                Đăng ký học
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Course Card 5 -->
                <div
                    class="group bg-white dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 overflow-hidden">
                        <img alt="HSK 2"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            data-alt="Group of students studying together"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBAVt2p-VEIeOBkd9lVoFDYh1sV5q-CqK3E7tfLeOpEQNX9dFFHvPQBPPst6-YmHB_nu2_gufuy7J-3kuoy6s4CAZumwpW40uOcZxZNfrXplzMCrYmTa7IOOBVUVhtGrncnKqLHbfIesl84n0hZ0wCis7qViZrJ0geP_W-C6iklhO_LTI1GZDTLM5jqeQuLahxeXL83PYiRX3CkhySLryUMuFK6lSqP3mgR2Geec3nBDESjrx0OCbDTiHxbckCbKMQJd4nkS1psbA" />
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-primary/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">HSK
                                2</span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3
                            class="font-poppins text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-primary transition-colors">
                            Everyday Conversations</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Practical speaking skills for daily life in
                            China, from dining to travel.</p>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                <img alt="Instructor" data-alt="Young female professional portrait"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDV6OmD-_56rSBoDHPElHL06EmL35TVEhwtL7W49Y3ZNlG8Rnh-3q9LI0Eb3XGkxB2aM9x6w6d_UqFqyVrYtWcVuJ34LkPPJkCxUlMPnvLDtLSZUsxy1j5ah2KuqpH146gHfVbfD_mZLR54cK1UhtCUhoBULcNan4WF3IBwq8KD5ul00TfS9YlFVQIShYdtdhzW6uRVldTqDUHm68qjThjti1kTNsBVtLl0rV5Vdkh3afHZd4PX58ZDwMea-u88Ygz-lHe2Rota4w" />
                            </div>
                            <div class="text-xs">
                                <p class="font-semibold text-slate-900 dark:text-slate-200">Ms. Lin Mei</p>
                                <p class="text-slate-500">36 Lessons</p>
                            </div>
                        </div>
                        <div
                            class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-xl font-bold text-slate-900 dark:text-white">$99.00</span>
                            <button
                                class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                                Đăng ký học
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Course Card 6 -->
                <div
                    class="group bg-white dark:bg-slate-900 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-48 overflow-hidden">
                        <img alt="HSK 5"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            data-alt="Technology and modern city lights in China"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuArZRfmwYt3FB_Lz8wlpF7cLSMqjjzJFo4pCfsWyQoCjtCOj4EV_RUhpoXgqDpQtpr_63PRt1r_ORC4VFVxJ4hA3SoAVtpMmA18BE7WC6Asd8aEQ81EeiPB2Uwkz4hjbYQ6FiOi7fRIk9zgMPWTHLuG1vBqkNAU9Z2J6rFTGpXe3H511NNw2fokhfx8FHE1QXyvAtgi5MHsWuqk4_fhjeC9GkYDH-3mVgriWO20IooeSWByZiCoKzNxGaL72Ts59zy2SXrk9JwKlA" />
                        <div class="absolute top-4 left-4">
                            <span
                                class="bg-primary/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">HSK
                                5</span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <h3
                            class="font-poppins text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-primary transition-colors">
                            Advanced HSK 5 Prep</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Intensive training for the HSK 5 exam focusing
                            on reading and writing speed.</p>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                <img alt="Instructor" data-alt="Asian man in professional suit"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCONipJUAyYgyAV4ANZbx008xyNQuYEzFzs_6CAyWiAjlQWAJ_aRiV0X-IbccIq_OVoepfqt8QvVwh0LdrFSeuEZ4x2GFp8FNYzxcVyxb0z5ufmLVoEr1WDXIObpnBJfx8t4afQNaqxXsAPKl29w9MmgGSKaIPaVog274uueR9wQLc_t9ot2Fob9lvuTCzMEU39QweA6NZWFrdZw--DZQCbsuqy1gFqxQFCsJkEBk1Gf2Kg5CUegdypAjiQzVB_PAu83RiR4jTlBQ" />
                            </div>
                            <div class="text-xs">
                                <p class="font-semibold text-slate-900 dark:text-slate-200">Mr. Zhao Long</p>
                                <p class="text-slate-500">52 Lessons</p>
                            </div>
                        </div>
                        <div
                            class="mt-auto pt-4 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-xl font-bold text-slate-900 dark:text-white">$159.00</span>
                            <button
                                class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                                Đăng ký học
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pagination -->
        <div class="flex justify-center mt-16">
            <nav class="flex items-center gap-2">
                <button
                    class="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">
                    <span class="material-symbols-outlined text-slate-400">chevron_left</span>
                </button>
                <button
                    class="w-10 h-10 rounded-lg bg-primary text-white font-bold flex items-center justify-center shadow-md shadow-primary/20">1</button>
                <button
                    class="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">2</button>
                <button
                    class="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">3</button>
                <span class="px-2 text-slate-400">...</span>
                <button
                    class="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">12</button>
                <button
                    class="w-10 h-10 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-800">
                    <span class="material-symbols-outlined text-slate-400">chevron_right</span>
                </button>
            </nav>
        </div>
    </main>
@endsection
