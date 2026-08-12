<div>
    <!-- Fixed Header -->
    <div class="shrink-0 px-6 lg:px-8 py-5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 z-10 shadow-sm relative">
        <div class="max-w-[1400px] mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ request()->is('teacher*') ? route('teacher.hsk-mock-exams.index') : route('admin.hsk-mock-exams.index') }}"
                    class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight">{{ $exam->title ?: 'Đang tải...' }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Trình Builder trực quan nội dung câu hỏi, hình ảnh và đáp án</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-xs text-emerald-600 font-bold" wire:loading wire:target="save">
                    Đang lưu...
                </div>
                <button type="button" wire:click="save" wire:loading.attr="disabled"
                    class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 flex items-center gap-2 disabled:opacity-50 transition-all active:scale-95 shrink-0">
                    <span class="material-symbols-outlined text-lg">save</span>
                    <span>Lưu Thay Đổi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Two Columns Scrollable Area -->
    <div class="flex-1 overflow-hidden p-6 lg:px-8 lg:py-6 bg-slate-50/50 dark:bg-[#131a1f]" style="height: calc(100vh - 100px);">
        <div class="max-w-[1400px] mx-auto h-full flex flex-col xl:flex-row gap-6 items-start">
            
            <!-- Main Content Area (Scrolls independently) -->
            <div class="flex-1 overflow-y-auto h-full min-w-0 w-full space-y-8 pb-32 pr-2" style="scrollbar-width: thin;" id="main-editor-scroll">
                <!-- Sections Builder -->
                @foreach($sectionsConfig as $sectionId => $sectionData)
                    @php
                        // Get the actual section record from exam relationships
                        $dbSection = $exam->sections->firstWhere('skill_type', $sectionId);
                        $groups = $dbSection ? $dbSection->questionGroups : collect();
                    @endphp
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-2xl text-slate-400">{{ $sectionData['icon'] }}</span>
                                <div>
                                    <h2 class="text-xl font-bold text-slate-900 dark:text-white uppercase">{{ $sectionData['name'] }}</h2>
                                    <p class="text-xs font-medium text-slate-500 mt-1">Quản lý các phần thi {{ strtolower($sectionData['name']) }}</p>
                                </div>
                            </div>
                            <button type="button" wire:click="toggleSection('{{ $sectionId }}')"
                                class="p-2 rounded-lg bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 transition-colors">
                                <span class="material-symbols-outlined">
                                    {{ in_array($sectionId, $expandedSections) ? 'expand_less' : 'expand_more' }}
                                </span>
                            </button>
                        </div>

                        @if(in_array($sectionId, $expandedSections))
                            <div class="space-y-6 pt-2">
                                @if($sectionId === 'listening')
                                    @php
                                        $actualSection = $this->exam->sections->firstWhere('skill_type', $sectionId);
                                    @endphp
                                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">File Âm thanh chung cho toàn bộ phần Nghe</label>
                                        
                                        @if($actualSection && $actualSection->audio_file)
                                            <div class="mb-3">
                                                <audio controls class="w-full max-w-md h-10">
                                                    <source src="{{ hsk_storage_url($actualSection->audio_file) }}" type="audio/mpeg">
                                                </audio>
                                            </div>
                                        @endif

                                        <div class="relative">
                                            <input type="file" wire:model="sectionAudios.{{ $sectionId }}" accept="audio/*" 
                                                class="w-full p-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                                            
                                            <div wire:loading wire:target="sectionAudios.{{ $sectionId }}" class="absolute inset-y-0 right-3 flex items-center">
                                                <div class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                                                <span class="ml-2 text-xs text-slate-500 font-bold">Đang tải lên...</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- List Parts of this Section here -->
                                @if($groups->isEmpty())
                                    <div class="text-center p-8 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">dashboard_customize</span>
                                        <h3 class="text-slate-500 font-bold">Chưa có phần thi nào</h3>
                                    </div>
                                @else
                                    <div class="space-y-4" wire:ignore.self x-data="{
                                        activeGroupId: null,
                                        init() {
                                            if (typeof Sortable !== 'undefined') {
                                                Sortable.create(this.$el, { 
                                                    handle: '.drag-handle', 
                                                    animation: 150, 
                                                    ghostClass: 'opacity-50',
                                                    onEnd: (e) => {
                                                        let order = Array.from(this.$el.children).map(el => el.dataset.id);
                                                        $wire.reorderParts(order);
                                                    } 
                                                });
                                            } else {
                                                console.error('SortableJS is not loaded');
                                            }
                                        }
                                    }">
                                        @foreach($groups as $group)
                                            <div id="part-{{ $group->id }}" wire:key="group-{{ $group->id }}" data-id="{{ $group->id }}" class="border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 shadow-sm overflow-hidden transition-colors scroll-mt-24" :class="activeGroupId === {{ $group->id }} ? 'ring-2 ring-primary border-primary' : ''">
                                                <div class="p-4 flex items-center justify-between cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/80" 
                                                     @click="activeGroupId = activeGroupId === {{ $group->id }} ? null : {{ $group->id }}">
                                                    <div>
                                                        <div wire:key="title-{{ $group->id }}" x-data="{ editingTitle: false, title: '{{ addslashes($group->title) }}' }" class="flex items-center gap-2">
                                                            <h3 x-show="!editingTitle" class="font-bold text-slate-800 dark:text-white flex items-center gap-2 cursor-pointer group/title" @click.stop="editingTitle = true">
                                                                <span x-text="title"></span>
                                                                <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover/title:text-primary transition-colors">edit</span>
                                                                <span x-show="activeGroupId === {{ $group->id }}" x-cloak class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] uppercase">Đang sửa</span>
                                                            </h3>
                                                            <input x-show="editingTitle" x-cloak 
                                                                x-model="title" 
                                                                @click.stop 
                                                                @keydown.enter="editingTitle = false; $wire.updateGroupTitle({{ $group->id }}, title)" 
                                                                @keydown.escape="editingTitle = false; title = '{{ addslashes($group->title) }}'" 
                                                                @blur="editingTitle = false; $wire.updateGroupTitle({{ $group->id }}, title)" 
                                                                class="text-sm font-bold border border-primary/50 focus:border-primary focus:ring-1 focus:ring-primary rounded px-2 py-1 bg-white dark:bg-slate-900 text-slate-900 dark:text-white w-48 sm:w-64 outline-none" 
                                                                x-ref="titleInput" 
                                                                x-effect="if(editingTitle) $nextTick(() => { $refs.titleInput.focus(); $refs.titleInput.select(); })">
                                                        </div>
                                                        @php
                                                            $typeConfig = collect($questionTypesConfig)->firstWhere('id', $group->group_type);
                                                        @endphp
                                                        <p class="text-xs text-slate-500 mt-1">Dạng: <span class="font-semibold text-primary">{{ $typeConfig['name'] ?? 'Không xác định' }}</span></p>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" onclick="event.stopPropagation()" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors drag-handle cursor-grab active:cursor-grabbing" title="Kéo để đổi thứ tự">
                                                            <span class="material-symbols-outlined text-xl">drag_indicator</span>
                                                        </button>
                                                        <button type="button" class="p-2 text-slate-400 hover:text-primary transition-colors" title="Chỉnh sửa chi tiết">
                                                            <span class="material-symbols-outlined" x-text="activeGroupId === {{ $group->id }} ? 'expand_less' : 'edit'">edit</span>
                                                        </button>
                                                        <button type="button" wire:click.stop="deletePart({{ $group->id }})" wire:confirm="Bạn có chắc chắn muốn xóa phần thi này và toàn bộ câu hỏi bên trong?"
                                                            class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Xóa">
                                                            <span class="material-symbols-outlined text-xl">delete</span>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Dynamic Component Area for Editing -->
                                                @if(isset($typeConfig['component']))
                                                    <div x-show="activeGroupId === {{ $group->id }}" x-cloak class="border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 p-6">
                                                        @if($typeConfig['is_implemented'] ?? false)
                                                            <livewire:is :component="$typeConfig['component']" :group="$group" :key="'editor-'.$group->id" />
                                                        @else
                                                            <div class="text-center py-8 text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-dashed border-amber-300 dark:border-amber-700/50">
                                                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">construction</span>
                                                                <p class="font-bold">Dạng câu hỏi này đang được xây dựng</p>
                                                                <p class="text-sm mt-1 opacity-80">Vui lòng chọn dạng khác hoặc chờ bản cập nhật tiếp theo.</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="flex justify-center relative">
                                    <button type="button" wire:click="showAddPartDropdown('{{ $sectionId }}')"
                                        class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm font-bold text-sm text-primary hover:border-primary/50 transition-colors">
                                        <span class="material-symbols-outlined text-lg">{{ $addingPartToSection == $sectionId ? 'close' : 'add' }}</span>
                                        {{ $addingPartToSection == $sectionId ? 'Đóng' : 'Thêm Part mới' }}
                                    </button>

                                    @if($addingPartToSection == $sectionId)
                                        <div class="absolute top-full mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 p-2">
                                            <div class="text-xs font-bold text-slate-400 uppercase px-3 py-2">Chọn Dạng Câu Hỏi</div>
                                            <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                                @foreach(collect($questionTypesConfig)->where('section', $sectionId)->where('is_implemented', true) as $qTypeId => $qType)
                                                    <button type="button" wire:click="addPart('{{ $sectionId }}', '{{ $qTypeId }}')"
                                                        class="w-full text-left px-3 py-2.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex flex-col gap-0.5">
                                                        <span class="font-bold text-sm text-slate-800 dark:text-white">{{ $qType['name'] }}</span>
                                                        <span class="text-[11px] text-slate-500 leading-tight">{{ $qType['description'] }}</span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Fixed Sidebar Navigator -->
            <aside class="hidden xl:flex w-72 shrink-0 flex-col h-full pb-12">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col h-full overflow-hidden">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="font-bold text-slate-800 dark:text-white text-sm">Điều hướng Builder</h3>
                        <p class="text-[11px] text-slate-500 mt-1">Cấu trúc nhanh của đề thi</p>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-6 custom-scrollbar">
                        @foreach($sectionsConfig as $sectionId => $sectionData)
                            @php
                                $dbSection = $exam->sections->firstWhere('skill_type', $sectionId);
                                $navGroups = $dbSection ? $dbSection->questionGroups->sortBy('order_index') : collect();
                            @endphp
                            @if($navGroups->count() > 0)
                                <div class="space-y-2">
                                    <h4 class="font-bold text-xs text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm text-{{ $sectionData['color'] ?? 'primary' }}">{{ $sectionData['icon'] }}</span>
                                        {{ $sectionData['name'] }}
                                    </h4>
                                    <div class="pl-3 space-y-1 border-l-2 border-slate-100 dark:border-slate-800 ml-2">
                                        @foreach($navGroups as $idx => $navGroup)
                                            @php
                                                $qTypeName = $navGroup->title ?: 'Part ' . ($idx + 1);
                                                if ($navGroup->group_type && isset(config('hsk_builder.question_types')[$navGroup->group_type])) {
                                                    $qTypeName = config('hsk_builder.question_types')[$navGroup->group_type]['name'];
                                                }
                                            @endphp
                                            <a href="#" @click.prevent="
                                                let target = document.getElementById('part-{{ $navGroup->id }}');
                                                let container = document.getElementById('main-editor-scroll');
                                                if (target && container) {
                                                    let scrollPos = target.getBoundingClientRect().top - container.getBoundingClientRect().top + container.scrollTop;
                                                    container.scrollTo({
                                                        top: scrollPos - 24,
                                                        behavior: 'smooth'
                                                    });
                                                }
                                            " class="block px-2 py-1.5 text-xs text-slate-600 dark:text-slate-400 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-md transition-colors truncate" title="{{ $qTypeName }}">
                                                <span class="font-bold text-slate-700 dark:text-slate-300 mr-1">{{ $idx + 1 }}.</span>
                                                {{ $qTypeName }}
                                                <span class="text-[10px] text-slate-400 ml-1">({{ $navGroup->questions->count() }} câu)</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                @php $hasAnyGroup = true; @endphp
                            @endif
                        @endforeach
                        
                        @if(!isset($hasAnyGroup))
                            <div class="text-center py-6">
                                <span class="material-symbols-outlined text-4xl text-slate-200 dark:text-slate-700 mb-2">inventory_2</span>
                                <p class="text-xs text-slate-400">Chưa có dữ liệu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>

        </div>
    </div>
</div>
