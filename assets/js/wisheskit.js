(function ($) {
    'use strict';

    var wisheskitInstances = [];

    function WishesKit($wrapper) {
        // Prevent double initialization
        if ($wrapper.data('wisheskit-init')) {
            return;
        }
        $wrapper.data('wisheskit-init', true);

        this.$wrapper = $wrapper;
        this.postId = $wrapper.data('post-id');
        this.perPage = parseInt($wrapper.data('per-page'), 10) || 15;
        this.currentPage = 1;
        this.totalPages = 1;
        this.isAdmin = !!(wisheskit_ajax && wisheskit_ajax.is_admin);
        this.isLoading = false;
        this.init();
        wisheskitInstances.push(this);
    }

    WishesKit.prototype = {
        init: function () {
            this.bindEvents();
            this.loadComments();
        },

        bindEvents: function () {
            var self = this;

            // Use .off().on() to prevent duplicate bindings
            this.$wrapper.find('.wisheskit-submit-btn').off('click.wisheskit').on('click.wisheskit', function () {
                self.submitComment(0);
            });

            this.$wrapper.off('click.wisheskit').on('click.wisheskit', '.wisheskit-reply-btn', function () {
                var commentId = $(this).data('comment-id');
                self.showReplyForm(commentId);
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-cancel-reply', function () {
                $(this).closest('.wisheskit-reply-form').removeClass('active');
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-reply-submit', function () {
                var parentId = $(this).data('parent-id');
                self.submitReply(parentId);
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-delete-btn', function () {
                var commentId = $(this).data('comment-id');
                self.deleteComment(commentId);
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-edit-btn', function () {
                var commentId = $(this).data('comment-id');
                self.showEditForm(commentId);
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-edit-save', function () {
                var commentId = $(this).data('comment-id');
                self.saveEdit(commentId);
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-edit-cancel', function () {
                var commentId = $(this).data('comment-id');
                self.cancelEdit(commentId);
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-popup-close-btn', function () {
                self.hidePopup();
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-popup-overlay', function (e) {
                if ($(e.target).hasClass('wisheskit-popup-overlay')) {
                    self.hidePopup();
                }
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-popup-copy-btn', function () {
                var code = $(this).data('code');
                self.copyToClipboard(code, $(this));
            });

            // Pagination
            this.$wrapper.on('click.wisheskit', '.wisheskit-page-prev', function () {
                if (self.currentPage > 1) {
                    self.currentPage--;
                    self.loadComments();
                }
            });

            this.$wrapper.on('click.wisheskit', '.wisheskit-page-next', function () {
                if (self.currentPage < self.totalPages) {
                    self.currentPage++;
                    self.loadComments();
                }
            });
        },

        loadComments: function () {
            var self = this;

            if (self.isLoading) {
                return;
            }
            self.isLoading = true;

            $.ajax({
                url: wisheskit_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'wisheskit_load',
                    nonce: wisheskit_ajax.nonce,
                    post_id: self.postId,
                    page: self.currentPage,
                    per_page: self.perPage
                },
                success: function (response) {
                    if (response.success) {
                        self.renderComments(response.data.comments);
                        self.updateCounters(response.data.counts, response.data.total);
                        self.totalPages = response.data.total_pages || 1;
                        self.currentPage = response.data.page || 1;
                        self.updatePagination();
                    }
                },
                complete: function () {
                    self.isLoading = false;
                }
            });
        },

        submitComment: function (parentId) {
            var self = this;
            var $form = this.$wrapper.find('.wisheskit-form');
            var name = $form.find('.wisheskit-input-name').val().trim();
            var message = $form.find('.wisheskit-input-message').val().trim();
            var rsvp = $form.find('.wisheskit-input-rsvp').val();
            var $btn = $form.find('.wisheskit-submit-btn');
            var $msg = $form.find('.wisheskit-form-message');

            if (!name || !message) {
                $msg.removeClass('success').addClass('error').text('Nama dan ucapan harus diisi.');
                return;
            }

            if (!rsvp) {
                $msg.removeClass('success').addClass('error').text('Pilih konfirmasi kehadiran.');
                return;
            }

            $btn.prop('disabled', true).text('Mengirim...');

            $.ajax({
                url: wisheskit_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'wisheskit_submit',
                    nonce: wisheskit_ajax.nonce,
                    post_id: self.postId,
                    name: name,
                    message: message,
                    rsvp: rsvp,
                    parent: parentId
                },
                success: function (response) {
                    if (response.success) {
                        $msg.removeClass('error').addClass('success').text(response.data.message);
                        $form.find('.wisheskit-input-name').val('');
                        $form.find('.wisheskit-input-message').val('');
                        $form.find('.wisheskit-input-rsvp').val('');
                        self.currentPage = 1;
                        self.loadComments();
                        self.showPopup();
                    } else {
                        $msg.removeClass('success').addClass('error').text(response.data.message);
                    }
                },
                error: function () {
                    $msg.removeClass('success').addClass('error').text('Terjadi kesalahan. Coba lagi.');
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Kirim');
                }
            });
        },

        submitReply: function (parentId) {
            var self = this;
            var $replyForm = this.$wrapper.find('.wisheskit-reply-form[data-parent="' + parentId + '"]');
            var name = $replyForm.find('.wisheskit-reply-name').val().trim();
            var message = $replyForm.find('.wisheskit-reply-message').val().trim();
            var $btn = $replyForm.find('.wisheskit-reply-submit');

            if (!name || !message) {
                return;
            }

            $btn.prop('disabled', true).text('Mengirim...');

            $.ajax({
                url: wisheskit_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'wisheskit_submit',
                    nonce: wisheskit_ajax.nonce,
                    post_id: self.postId,
                    name: name,
                    message: message,
                    rsvp: '',
                    parent: parentId
                },
                success: function (response) {
                    if (response.success) {
                        $replyForm.removeClass('active');
                        $replyForm.find('input, textarea').val('');
                        self.loadComments();
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Kirim');
                }
            });
        },

        deleteComment: function (commentId) {
            var self = this;

            if (!confirm('Yakin ingin menghapus komentar ini?')) {
                return;
            }

            $.ajax({
                url: wisheskit_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'wisheskit_delete',
                    nonce: wisheskit_ajax.nonce,
                    comment_id: commentId
                },
                success: function (response) {
                    if (response.success) {
                        self.loadComments();
                    } else {
                        alert(response.data.message || 'Gagal menghapus komentar.');
                    }
                }
            });
        },

        showEditForm: function (commentId) {
            var $item = this.$wrapper.find('.wisheskit-comment-item[data-comment-id="' + commentId + '"]').first();
            $item.find('> .wisheskit-comment .wisheskit-comment-body > .wisheskit-comment-content').hide();
            $item.find('> .wisheskit-comment .wisheskit-comment-body > .wisheskit-comment-meta').hide();
            $item.find('> .wisheskit-comment .wisheskit-comment-body > .wisheskit-edit-form').addClass('active');
        },

        cancelEdit: function (commentId) {
            var $item = this.$wrapper.find('.wisheskit-comment-item[data-comment-id="' + commentId + '"]').first();
            $item.find('> .wisheskit-comment .wisheskit-comment-body > .wisheskit-comment-content').show();
            $item.find('> .wisheskit-comment .wisheskit-comment-body > .wisheskit-comment-meta').show();
            $item.find('> .wisheskit-comment .wisheskit-comment-body > .wisheskit-edit-form').removeClass('active');
        },

        saveEdit: function (commentId) {
            var self = this;
            var $item = this.$wrapper.find('.wisheskit-comment-item[data-comment-id="' + commentId + '"]').first();
            var newContent = $item.find('.wisheskit-edit-textarea').val().trim();

            if (!newContent) {
                return;
            }

            var $btn = $item.find('.wisheskit-edit-save');
            $btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: wisheskit_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'wisheskit_edit',
                    nonce: wisheskit_ajax.nonce,
                    comment_id: commentId,
                    content: newContent
                },
                success: function (response) {
                    if (response.success) {
                        self.loadComments();
                    } else {
                        alert(response.data.message || 'Gagal mengedit komentar.');
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Simpan');
                }
            });
        },

        showReplyForm: function (commentId) {
            this.$wrapper.find('.wisheskit-reply-form').removeClass('active');
            var $form = this.$wrapper.find('.wisheskit-reply-form[data-parent="' + commentId + '"]');
            $form.addClass('active');
            $form.find('.wisheskit-reply-name').focus();
        },

        updateCounters: function (counts, total) {
            this.$wrapper.find('.wisheskit-total-count').text(total);
            this.$wrapper.find('.wisheskit-count-hadir').text(counts.hadir || 0);
            this.$wrapper.find('.wisheskit-count-tidak_hadir').text(counts.tidak_hadir || 0);
            this.$wrapper.find('.wisheskit-count-ragu').text(counts.ragu || 0);
        },

        updatePagination: function () {
            var $pagination = this.$wrapper.find('.wisheskit-pagination');
            var $prev = this.$wrapper.find('.wisheskit-page-prev');
            var $next = this.$wrapper.find('.wisheskit-page-next');

            if (this.totalPages <= 1) {
                $pagination.hide();
                return;
            }

            $pagination.show();
            this.$wrapper.find('.wisheskit-current-page').text(this.currentPage);
            this.$wrapper.find('.wisheskit-total-pages').text(this.totalPages);

            $prev.prop('disabled', this.currentPage <= 1);
            $next.prop('disabled', this.currentPage >= this.totalPages);
        },

        renderComments: function (comments) {
            var html = this.buildCommentsHTML(comments);
            this.$wrapper.find('.wisheskit-comments-list').html(html);
        },

        buildCommentsHTML: function (comments) {
            var output = '';
            for (var i = 0; i < comments.length; i++) {
                output += this.buildSingleComment(comments[i]);
            }
            return output;
        },

        buildSingleComment: function (comment) {
            var self = this;
            var initial = comment.author.charAt(0).toUpperCase();
            var rsvpBadge = self.getRsvpBadge(comment.rsvp);
            var h = '';

            h += '<div class="wisheskit-comment-item" data-comment-id="' + comment.id + '">';
            h += '<div class="wisheskit-comment">';
            h += '<div class="wisheskit-avatar">' + initial + '</div>';
            h += '<div class="wisheskit-comment-body">';
            h += '<span class="wisheskit-comment-author">' + self.escapeHtml(comment.author) + '</span>';
            h += rsvpBadge;
            h += '<div class="wisheskit-comment-content">' + comment.content + '</div>';
            h += '<div class="wisheskit-comment-meta">';
            h += '<span class="wisheskit-comment-date">&#9201; ' + comment.date + '</span>';
            h += '<span class="wisheskit-reply-btn" data-comment-id="' + comment.id + '">Reply</span>';

            if (self.isAdmin) {
                h += '<span class="wisheskit-edit-btn" data-comment-id="' + comment.id + '">Edit</span>';
                h += '<span class="wisheskit-delete-btn" data-comment-id="' + comment.id + '">Hapus</span>';
            }

            h += '</div>';

            if (self.isAdmin) {
                h += '<div class="wisheskit-edit-form">';
                h += '<textarea class="wisheskit-edit-textarea" rows="3">' + self.escapeHtml(comment.raw_content || '') + '</textarea>';
                h += '<div class="wisheskit-edit-actions">';
                h += '<button type="button" class="wisheskit-edit-save" data-comment-id="' + comment.id + '">Simpan</button>';
                h += '<button type="button" class="wisheskit-edit-cancel" data-comment-id="' + comment.id + '">Batal</button>';
                h += '</div>';
                h += '</div>';
            }

            h += '</div>';
            h += '</div>';

            h += '<div class="wisheskit-reply-form" data-parent="' + comment.id + '">';
            h += '<input type="text" class="wisheskit-reply-name" placeholder="Nama" />';
            h += '<textarea class="wisheskit-reply-message" placeholder="Balas ucapan..." rows="2"></textarea>';
            h += '<button type="button" class="wisheskit-reply-submit" data-parent-id="' + comment.id + '">Kirim</button>';
            h += '<button type="button" class="wisheskit-cancel-reply">Batal</button>';
            h += '</div>';

            if (comment.children && comment.children.length > 0) {
                h += '<div class="wisheskit-comment-children">';
                h += self.buildCommentsHTML(comment.children);
                h += '</div>';
            }

            h += '</div>';

            return h;
        },

        getRsvpBadge: function (rsvp) {
            if (rsvp === 'hadir') {
                return '<span class="wisheskit-badge-hadir" title="Hadir">&#10003;</span>';
            } else if (rsvp === 'tidak_hadir') {
                return '<span class="wisheskit-badge-tidak_hadir" title="Tidak Hadir">&#10007;</span>';
            } else if (rsvp === 'ragu') {
                return '<span class="wisheskit-badge-ragu" title="Masih Ragu">?</span>';
            }
            return '';
        },

        escapeHtml: function (text) {
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        },

        showPopup: function () {
            var $popup = this.$wrapper.find('.wisheskit-popup-overlay');
            if ($popup.length) {
                $popup.fadeIn(300);
                $('body').css('overflow', 'hidden');
            }
        },

        hidePopup: function () {
            var $popup = this.$wrapper.find('.wisheskit-popup-overlay');
            $popup.fadeOut(300);
            $('body').css('overflow', '');
        },

        copyToClipboard: function (text, $btn) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function () {
                    $btn.addClass('copied');
                    setTimeout(function () {
                        $btn.removeClass('copied');
                    }, 2000);
                });
            } else {
                var $temp = $('<input>');
                $('body').append($temp);
                $temp.val(text).select();
                document.execCommand('copy');
                $temp.remove();
                $btn.addClass('copied');
                setTimeout(function () {
                    $btn.removeClass('copied');
                }, 2000);
            }
        }
    };

    // Only use Elementor hook if in Elementor context, otherwise use document.ready
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/wisheskit.default', function ($scope) {
                var $wrapper = $scope.find('.wisheskit-wrapper');
                if ($wrapper.length) {
                    new WishesKit($wrapper);
                }
            });
        }
    });

    // Fallback for non-Elementor pages or when Elementor frontend doesn't fire
    $(document).ready(function () {
        setTimeout(function () {
            $('.wisheskit-wrapper').each(function () {
                new WishesKit($(this));
            });
        }, 100);
    });

})(jQuery);
