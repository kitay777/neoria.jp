{{-- resources/views/emails/new_work_in_category.blade.php --}}
<p>{{ $user->name }}さん、こんにちは。</p>

<p>あなたが出品しているカテゴリ「{{ $work->category->name ?? '未分類' }}」に、新しい仕事が登録されました。</p>

<p><strong>{{ $work->title }}</strong></p>
<p>納期：{{ $work->execution_date ?? '未設定' }}</p>
<p>詳細：{{ Str::limit($work->description, 80) }}</p>

<p>
    <a href="{{ route('works.show', $work) }}">この仕事を見る</a>
</p>
