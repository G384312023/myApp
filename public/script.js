document.addEventListener("DOMContentLoaded", () => {
    const taskListWrapper = document.querySelector('#task-list-wrapper');
    const sortSelect = document.querySelector('#sort-select');

    // --- ヘルパー関数: タスクリストを非同期で更新 ---
    const updateTaskList = async () => {
        // 現在のソート順を取得
        const sortOrder = sortSelect ? sortSelect.value : 'desc';
        
        try {
            // ローディング表示（任意）
            if(taskListWrapper) taskListWrapper.innerHTML = '<p>読み込み中...</p>';

            const response = await fetch(`src/controllers/AjaxController.php?sort=${sortOrder}`);
            if (!response.ok) {
                throw new Error('サーバーとの通信に失敗しました。');
            }
            const tasksHtml = await response.text();
            if(taskListWrapper) taskListWrapper.innerHTML = tasksHtml;

            // HTMLを置き換えた後に、再度イベントリスナーとMarkdown処理を設定し直す
            initializeTaskList();

        } catch (error) {
            console.error('タスクリストの更新に失敗しました:', error);
            if(taskListWrapper) taskListWrapper.innerHTML = '<p>タスクの読み込みに失敗しました。</p>';
        }
    };

    // --- タスクリストの初期化（イベントリスナー設定など） ---
    const initializeTaskList = () => {
        processMarkdown();
        addCheckboxEventListeners();
    };

    // --- Markdown処理 ---
    const processMarkdown = () => {
        if (typeof marked !== 'undefined') {
            const markdownElements = document.querySelectorAll(".markdown-content");
            markdownElements.forEach(element => {
                const markdownText = element.dataset.markdown;
                if (markdownText) {
                    element.innerHTML = marked.parse(markdownText);
                }
            });
        }
    };

    // --- チェックボックスのイベント処理 ---
    const addCheckboxEventListeners = () => {
        const checkboxes = document.querySelectorAll(".task-checkbox");
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", async (event) => {
                const taskItem = event.target.closest(".task-item");
                const taskId = taskItem.dataset.taskId;
                const isDone = event.target.checked;

                try {
                    const response = await fetch("UpdateTaskStatus.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ id: taskId, is_done: isDone ? 1 : 0 }),
                    });

                    if (response.ok) {
                        // DB更新が成功したら、タスクリスト全体を再読み込みして反映
                        await updateTaskList();
                    } else {
                        const errorData = await response.json();
                        console.error("タスク状態の更新に失敗しました:", errorData.message);
                        alert(`タスク状態の更新に失敗しました: ${errorData.message || "不明なエラー"}`);
                        event.target.checked = !isDone; // チェックボックスを元に戻す
                    }
                } catch (error) {
                    console.error("通信エラー:", error);
                    alert("通信エラーが発生しました。");
                    event.target.checked = !isDone;
                }
            });
        });
    };

    // --- 並べ替えのイベント処理 ---
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            updateTaskList();
        });
    }

    // --- 初回読み込み時の初期化 ---
    initializeTaskList();
});
