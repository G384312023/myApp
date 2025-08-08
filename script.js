document.addEventListener("DOMContentLoaded", () => {
  // ページ上の全ての '.task-checkbox' クラスを持つチェックボックス要素を取得
  const checkboxes = document.querySelectorAll(".task-checkbox");

  // 取得した各チェックボックスに 'change' イベントリスナーを設定
  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", async (event) => {
      // ★ポイント1: イベントが発生したチェックボックスの親要素 (.task-item、つまりカード全体) を取得
      const taskItem = event.target.closest(".task-item");

      // ★ポイント2: data-task-id 属性からタスクIDを取得
      // HTMLの <div class="task-item" data-task-id="XXX"> から値を取得します
      const taskId = taskItem.dataset.taskId;

      // ★ポイント3: チェックボックスの現在の状態（チェックされているか否か）を取得
      // true なら完了、false なら未完了
      const isDone = event.target.checked;

      // ★ポイント4: サーバーに非同期でデータ (タスクIDと完了状態) を送信
      try {
        const response = await fetch("UpdateTaskStatus.php", {
          // 送信先のPHPファイル
          method: "POST", // データを送信するのでPOSTメソッド
          headers: {
            "Content-Type": "application/json", // 送信するデータがJSON形式だとPHPに伝える
          },
          // JavaScriptのオブジェクトをJSON形式の文字列に変換して送信
          body: JSON.stringify({ id: taskId, is_done: isDone ? 1 : 0 }), // 1は完了、0は未完了として送信
        });

        // ★ポイント5: サーバーからの応答が成功したかを確認
        if (response.ok) {
          // HTTPステータスコードが200番台（成功）の場合
          // サーバーでの更新が成功したら、フロントエンドの見た目を更新
          if (isDone) {
            taskItem.classList.add("completed"); // 'completed' クラスを追加
          } else {
            taskItem.classList.remove("completed"); // 'completed' クラスを削除
          }
          // 必要であれば、完了/未完了のテキストも更新
          const statusText = taskItem.querySelector(".status-text");
          if (statusText) {
            statusText.textContent = isDone ? "完了" : "未完了";
          }
        } else {
          // サーバーからの応答がエラーだった場合
          const errorData = await response.json(); // PHPからのエラーメッセージをJSONで受け取る
          console.error("タスク状態の更新に失敗しました:", errorData.message);
          alert(
            `タスク状態の更新に失敗しました: ${
              errorData.message || "不明なエラー"
            }`
          );
          // 失敗した場合は、チェックボックスの状態を元の状態に戻す
          event.target.checked = !isDone;
        }
      } catch (error) {
        // ネットワーク接続の問題など、リクエスト自体が失敗した場合
        console.error("通信エラー:", error);
        alert(
          "通信エラーが発生しました。インターネット接続を確認してください。"
        );
        // 失敗した場合は、チェックボックスの状態を元の状態に戻す
        event.target.checked = !isDone;
      }
    });
  });
});
