<div class="media media-comment">
    <div class="media-left">
        <a href="javascript:;">
            <img class="media-object" src="<?= $comment['head']; ?>" >
        </a>
    </div>
    <div class="media-body" data-rid="<?= $comment['id']; ?>">
        <h4 class="media-heading"><?= $comment['author']; ?></h4>
        <div class="media-body-content"><?= $comment['content']; ?></div>
        <div class="media-body-opt">
            <span class="opt-time" title="<?= $comment['showDate']; ?>"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= $comment['showTime']; ?></span>
            <span class="opt-like"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <m><?= $comment['like']; ?></m></span>
            <a href="#reply_area" class="opt-reply"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> 回复</a>
        </div>
        <div class="reply-list" id="reply_list_<?= $comment['id']; ?>">
            <?php
            if (isset($comment['replyList']) && $comment['replyList']) {
                foreach ($comment['replyList'] as $k => $reply) {
                    ?>
                    <div class="media">
                        <div class="media-left">
                            <a href="javascript:;">
                                <img class="media-object" src="<?= $reply['head']; ?>" >
                            </a>
                        </div>
                        <div class="media-body" data-rid="<?= $reply['id']; ?>">
                            <h4 class="media-heading"><?= $reply['author']; ?></h4>
                            <div class="media-body-content"><?= $reply['content']; ?></div>
                            <div class="media-body-opt">
                                <span class="opt-time" title="<?= $reply['showDate']; ?>"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?= $reply['showTime']; ?></span>
                                <span class="opt-like"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> <m><?= $reply['like']; ?></m></span>
                                <a href="#reply_area" class="opt-reply"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> 回复</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>