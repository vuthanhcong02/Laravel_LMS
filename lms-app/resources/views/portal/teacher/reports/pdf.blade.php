<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Báo cáo học tập') }} - {{ $student->first_name }} {{ $student->last_name }}</title>
    <style>
        {!! file_get_contents(resource_path('css/pdf.css')) !!}
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ __('BÁO CÁO KẾT QUẢ HỌC TẬP') }}</h1>
        <p>{{ __('Học sinh') }}: <strong>{{ $student->first_name }} {{ $student->last_name }}</strong></p>
        <p>{{ __('Email') }}: {{ $student->email }} | {{ __('Tham gia') }}: {{ $student->created_at->format('d/m/Y') }}</p>
    </div>

    <table class="stats">
        <tr>
            <td>
                <h3>{{ $stats['total_courses'] }}</h3>
                <p>{{ __('Khóa học đang học') }}</p>
            </td>
            <td>
                <h3>{{ $stats['avg_assignments'] }}</h3>
                <p>{{ __('Điểm TB Bài tập') }}</p>
            </td>
            <td>
                <h3>{{ $stats['avg_quizzes'] }}</h3>
                <p>{{ __('Điểm TB Bài kiểm tra') }}</p>
            </td>
        </tr>
    </table>

    <h3 style="color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">{{ __('Lịch sử đánh giá') }}</h3>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Loại') }}</th>
                <th>{{ __('Bài đánh giá') }}</th>
                <th>{{ __('Ngày nộp') }}</th>
                <th class="text-center">{{ __('Trạng thái') }}</th>
                <th class="text-right">{{ __('Điểm') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $history)
            <tr>
                <td>
                    <span class="badge {{ $history['color'] === 'emerald' ? 'badge-emerald' : 'badge-orange' }}">
                        {{ __($history['type']) }}
                    </span>
                </td>
                <td>
                    <div style="font-weight: bold; margin-bottom: 3px;">{{ $history['title'] }}</div>
                    <div style="font-size: 11px; color: #64748b;">{{ $history['course'] }}</div>
                </td>
                <td style="font-size: 12px;">{{ $history['date'] }}</td>
                <td class="text-center">
                    <span class="badge {{ $history['status'] === 'Đã chấm' ? 'badge-emerald' : 'badge-amber' }}">
                        {{ __($history['status']) }}
                    </span>
                </td>
                <td class="text-right">
                    @if($history['score'])
                        <span class="{{ $history['score'] >= 8 ? 'score-high' : ($history['score'] >= 5 ? 'score-mid' : 'score-low') }}">
                            {{ $history['score'] }}/10
                        </span>
                    @else
                        <span style="color: #94a3b8; font-size: 12px;">--/10</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ __('Báo cáo được xuất tự động từ hệ thống LMS vào lúc') }} {{ now()->format('H:i d/m/Y') }}
    </div>

</body>
</html>
