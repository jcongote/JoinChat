<?php
    if (isProduction()) {
        echo $this->Html->script('ydn.db-isw-core-e-cur-qry-dev-raw.min');
    } else {
        echo $this->Html->script('ydn.db-isw-core-e-cur-qry-dev-raw');
    }
?>
<script type="text/javascript">
    var guest, db, userId, idGuest = 1;

    <?php if (!AuthComponent::user('guest') || AuthComponent::user('guest') !== true) : ?>
        userId = "<?php echo AuthComponent::user('idUsuario'); ?>";
    <?php endif; ?>

    $(document).ready(function() {
        var schema = {
          stores: [{
            name: 'guest',
            autoIncrement: false,
            indexes: [
                {
                    name: 'nickname'
                },
                {
                    name: 'email'
                }
            ]
          },
          {
            name: 'history',
            autoIncrement: true,
            keyPath: 'id',
            type: 'INTEGER',
            indexes: [
                {
                    name: 'id',
                    type: 'INTEGER',
                    unique: true
                },
                {
                    name: 'chatId',
                    type: 'INTEGER'
                },
                {
                    name: 'timestamp',
                    type: 'INTEGER'
                },
                {
                    name: 'message',
                    type: 'TEXT'
                },
                {
                    name: 'filename',
                    type: 'TEXT'
                },
                {
                    name: 'type',
                    /**
                     * Must be 'message' or 'file'
                     */
                    type: 'TEXT'
                },
                {
                    name: 'received',
                    /**
                     * Must be 0 or 1
                     */
                    type: 'INTEGER'
                }
            ]
          }]
        };
        db = new ydn.db.Storage('joinchat', schema);

        function init() {
            db.get('guest', idGuest)
                .done(function(record) {
                    if (record) {
                        if (userId) {
                            db.remove('guest', idGuest).fail(failFn);
                        } else {
                            guest = record;
                            guestFound = guestFound || function() {};
                            if (guestFound) {
                                guestFound();
                            }
                        }
                    }
                })
                .fail(failFn);
        }

        function guestFound() {
            if ($userNickname = $("#UserNickname")) {
                $userNickname.val(guest.nickname);
            }
            if ($userEmail = $("#UserEmail")) {
                $userEmail.val(guest.email);
            }
        }

        function failFn(e) {
            console.warn(e.message);
        }

        db.onReady(function() {
            init();
            if (typeof(afterInit) === 'function') {
                afterInit();
            }
        });
    });
</script>