<?php if (empty($incompleteTasks) && empty($completedTasks)): ?>
    <p>まだタスクがありません。新しいタスクを作成しましょう！</p>
<?php else: ?>
    
    <?php if (!empty($incompleteTasks)): ?>
        <div class="task-list-container">
            <?php foreach($incompleteTasks as $task): ?>
              <div class="task-item <?= $task['is_done'] ? 'completed' : '' ?>" data-task-id="<?= $task['id'] ?>">
                <div class="task-header">
                  <input type="checkbox" class="task-checkbox" <?= $task['is_done'] ? 'checked' : '' ?>>
                  <h3 class="task-title"><?= htmlspecialchars($task['title'])?></h3>
                  <span class="task-date">
                    <?php
                        $createdTs = strtotime($task['created_at']);
                        $updatedTs = !empty($task['updated_at']) ? strtotime($task['updated_at']) : 0;
                        $isUpdated = ($updatedTs && ($updatedTs - $createdTs > 1));
                        $displayDateString = $isUpdated ? $task['updated_at'] : $task['created_at'];
                        $label = $isUpdated ? '(更新)' : '(作成)';
                        $formattedDate = DateFormatter::format($displayDateString);
                        echo htmlspecialchars($formattedDate . ' ' . $label);
                    ?>
                  </span>
                </div>
                <div class="task-description markdown-content" data-markdown="<?= htmlspecialchars($task['description'])?>">
                  <noscript><?= htmlspecialchars($task['description'])?></noscript>
                  <div class="markdown-fallback"><?= htmlspecialchars($task['description'])?></div>
                </div>
                <div class="task-actions">
                  <span class="status-text"><?= $task['is_done'] ? '完了' : '未完了'?></span>
                  <a href="Edit.php?id=<?= $task['id']?>" class="button edit-button">編集</a>
                  <a href="Delete.php?id=<?= $task['id']?>" class="button delete-button" onclick="return confirm('本当に削除しますか？');">削除</a>
                </div>
              </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($incompleteTasks) && !empty($completedTasks)): ?>
        <hr class="task-separator">
    <?php endif; ?>

    <?php if (!empty($completedTasks)): ?>
        <h3>完了済みのタスク</h3>
        <div class="task-list-container">
            <?php foreach($completedTasks as $task): ?>
              <div class="task-item <?= $task['is_done'] ? 'completed' : '' ?>" data-task-id="<?= $task['id'] ?>">
                <div class="task-header">
                  <input type="checkbox" class="task-checkbox" <?= $task['is_done'] ? 'checked' : '' ?>>
                  <h3 class="task-title"><?= htmlspecialchars($task['title'])?></h3>
                  <span class="task-date">
                    <?php
                        $createdTs = strtotime($task['created_at']);
                        $updatedTs = !empty($task['updated_at']) ? strtotime($task['updated_at']) : 0;
                        $isUpdated = ($updatedTs && ($updatedTs - $createdTs > 1));
                        $displayDateString = $isUpdated ? $task['updated_at'] : $task['created_at'];
                        $label = $isUpdated ? '(更新)' : '(作成)';
                        $formattedDate = DateFormatter::format($displayDateString);
                        echo htmlspecialchars($formattedDate . ' ' . $label);
                    ?>
                  </span>
                </div>
                <div class="task-description markdown-content" data-markdown="<?= htmlspecialchars($task['description'])?>">
                  <noscript><?= htmlspecialchars($task['description'])?></noscript>
                  <div class="markdown-fallback"><?= htmlspecialchars($task['description'])?></div>
                </div>
                <div class="task-actions">
                  <span class="status-text"><?= $task['is_done'] ? '完了' : '未完了'?></span>
                  <a href="Edit.php?id=<?= $task['id']?>" class="button edit-button">編集</a>
                  <a href="Delete.php?id=<?= $task['id']?>" class="button delete-button" onclick="return confirm('本当に削除しますか？');">削除</a>
                </div>
              </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php endif; ?>
