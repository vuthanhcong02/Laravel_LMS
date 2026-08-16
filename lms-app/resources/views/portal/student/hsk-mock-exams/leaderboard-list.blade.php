@if(isset($leaderboard) && $leaderboard->isNotEmpty())
    @foreach($leaderboard as $index => $result)
        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border {{ auth()->check() && auth()->id() == $result->user_id ? 'bg-primary/5 border-primary/10' : 'border-transparent' }}">
            @if($index == 0)
                <div class="w-8 flex justify-center">
                    <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center border border-amber-300 dark:border-amber-600 shadow-[0_0_10px_rgba(251,191,36,0.3)]">
                        <span class="text-amber-500 font-black text-sm">1</span>
                    </div>
                </div>
            @elseif($index == 1)
                <div class="w-8 flex justify-center">
                    <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center border border-slate-300 dark:border-slate-500">
                        <span class="text-slate-500 font-black text-sm">2</span>
                    </div>
                </div>
            @elseif($index == 2)
                <div class="w-8 flex justify-center">
                    <div class="w-7 h-7 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center border border-orange-300 dark:border-orange-700">
                        <span class="text-orange-600 font-black text-sm">3</span>
                    </div>
                </div>
            @else
                <div class="w-8 flex justify-center">
                    <div class="font-bold text-slate-400 text-xs">{{ $index + 1 }}</div>
                </div>
            @endif
            
            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex-shrink-0 border border-slate-300 dark:border-slate-600 flex items-center justify-center overflow-hidden">
                @if($result->user)
                    <img src="{{ $result->user->avatar_url }}" alt="{{ $result->user->first_name }} {{ $result->user->last_name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xs font-bold text-slate-500">?</span>
                @endif
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 dark:text-white truncate">
                    {{ auth()->check() && auth()->id() == $result->user_id ? 'Bạn' : (trim($result->user->first_name . ' ' . $result->user->last_name) ?: 'Người dùng') }}
                </p>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">
                    {{ $result->mockExam && $result->mockExam->hskLevel ? strtoupper($result->mockExam->hskLevel->level_code) : 'HSK' }}
                </p>
            </div>
            
            <div class="text-right">
                <p class="text-sm font-black text-slate-700 dark:text-slate-300">
                    {{ $result->total_score }}
                </p>
                <p class="text-[10px] text-slate-500 flex items-center justify-end gap-1">
                    <span class="material-symbols-outlined text-[12px]">timer</span>
                    {{ floor($result->duration_seconds / 60) }}m {{ $result->duration_seconds % 60 }}s
                </p>
            </div>
        </div>
    @endforeach

    @if(auth()->check() && isset($currentUserRank) && $currentUserRank > 10)
        <div class="flex justify-center my-1">
            <div class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600 mx-1"></div>
            <div class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600 mx-1"></div>
            <div class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600 mx-1"></div>
        </div>
        <div class="flex items-center gap-3 p-3 rounded-xl bg-primary/5 border border-primary/20 transition-colors shadow-sm">
            <div class="w-8 flex justify-center">
                <div class="font-black text-primary text-xs">{{ $currentUserRank }}</div>
            </div>
            
            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex-shrink-0 border border-slate-300 dark:border-slate-600 flex items-center justify-center overflow-hidden">
                @if($currentUserResult->user)
                    <img src="{{ $currentUserResult->user->avatar_url }}" alt="{{ $currentUserResult->user->first_name }} {{ $currentUserResult->user->last_name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xs font-bold text-slate-500">?</span>
                @endif
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 dark:text-white truncate">Bạn</p>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">
                    {{ $currentUserResult->mockExam && $currentUserResult->mockExam->hskLevel ? strtoupper($currentUserResult->mockExam->hskLevel->level_code) : 'HSK' }}
                </p>
            </div>
            
            <div class="text-right">
                <p class="text-sm font-black text-slate-700 dark:text-slate-300">
                    {{ $currentUserResult->total_score }}
                </p>
                <p class="text-[10px] text-slate-500 flex items-center justify-end gap-1">
                    <span class="material-symbols-outlined text-[12px]">timer</span>
                    {{ floor($currentUserResult->duration_seconds / 60) }}m {{ $currentUserResult->duration_seconds % 60 }}s
                </p>
            </div>
        </div>
    @endif
@else
    <div class="py-8 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Chưa có ai hoàn thành đề thi nào.</p>
    </div>
@endif
