<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>グランド予約ダッシュボード</title>
  <link rel="stylesheet" href="/css/calendar.css">
</head>
<body>
  <header>
    <div class="header-left">
      <h1>グランド予約</h1>
    </div>
    <div class="header-right">
      <a href="#">アカウント情報</a>
    </div>
  </header>

  <main>
    <section class="ground-selection">
      <h2>予約したいグラウンドを選択してください</h2>
      <form>
        <label for="ground-type">グラウンド種類:</label>
        <select id="ground-type" name="ground-type">
          <option value="フルコート">フルコート</option>
          <option value="ハーフコート（南側）">ハーフコート（南側）</option>
          <option value="ハーフコート（北側）">ハーフコート（北側）</option>
        </select>
      </form>
    </section>

    <section class="calendar-display">
      <h2>カレンダー表示</h2>
      <div class="calendar-controls">
        <button id="weekly-view">週間表示</button>
        <button id="monthly-view">月間表示</button>
      </div>
    </section>

    <section>
      <div id="calendar-controls">
        <button id="prev-month">前月</button>
        <button id="day">今日</button>
        <button id="next-month">翌月</button>
      </div>
    </section>

    <div id="calendar"></div>
  </main>

  <!-- JavaScriptファイルの読み込み -->
  <script type="module" src="/js/calendar.js"></script>
</body>
</html>
