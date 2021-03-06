(function ($) {

	var slice = Array.prototype.slice;



	/**
	 * 常用工具函数
	 */
	(function () {
		$.extend({
			yRefresh: function (delay) {
				if (! delay) {
					delay = 0;
				}
				setTimeout(function () {location.reload()}, delay);
			},
			yRedirect: function (url, delay) {
				if (! delay) {
					delay = 0;
				}
				setTimeout(function () {location.href = url;}, delay);
			},
			yBack: function (delay) {
				if (! delay) {
					delay = 0;
				}
				setTimeout(function () {history.back();}, delay);
			},
			yToInt: function (num) {
				return parseInt(num, 10);
			},
			yFormatNum: function (num, decimals) {
				if (! decimals) {
					decimals = 2;
				} else {
					decimals = this.yToInt(decimals);
				}
				if (0 === decimals) {
					return this.yToInt(num);
				} else {
					return new Number(num).toFixed(decimals);
				}
			},
			yFormatPrice: function (price) {
				return this.yFormatNum(price / 100);
			},
			yFormatPercent: function (percent) {
				return this.yFormatNum(percent * 100) + '%';
			}
		});

		$.fn.extend({
			yDisabled: function () {
				return $(this).hasClass('disabled');
			},
			yEnabled: function () {
				return ! $(this).hasClass('disabled');
			},
			yDisable: function () {
				return $(this).addClass('disabled').attr('disabled', 'disabled');
			},
			yEnable: function () {
				return $(this).removeClass('disabled').removeAttr('disabled');
			},
			ySetForm: function (valObj, change) {
				var $this = $(this);
				$.each(valObj, function (k, v) {
					var $item = $this.find('[name="' + k + '"]');
					if ($item.is(':checkbox, :radio')) {
						if (v && '0' !== v) {
							$item.attr('checked', 'checked');
						} else {
							$item.removeAttr('checked');
						}
					} else {
						$item.val(v);
					}
					$item.removeClass('error');
					if (change) {
						$item.change();
					}
				});
				return $this;
			},
			yShow: function () {
				return $(this).removeClass('hide');
			},
			yHide: function () {
				return $(this).addClass('hide');
			}
		});
	})(jQuery);



	/**
	 * 弹框
	 */
	(function () {
		var createUi = function () {
			return $('<div>')
				.addClass('popup');
		};
		var createUiTitle = function () {
			return $('<div>')
				.addClass('popup-hd clearfix')
				.append($('<h3>').addClass('popup-tit fl'))
				.append($('<a href="javascript:;"></a>').addClass('btn-popup-close fr'));
		};
		var createUiContent = function () {
			return $('<div>').addClass('popup-con');
		};
		var createUiButton = function () {
			return $('<div>').addClass('pop-btns');
		};
		var setPosition = function ($el) {
			var w = $el.width(),
				h = $el.height();
			$el.css('left', '50%');
			$el.css('margin-left', -w / 2);
			$el.css('top', "40%");
			$el.css('margin-top', -h * 2 / 5);
		};
		var methods = {
			init: function(options) {
				return this.each(function() {
					var $this = $(this),
						settings = $.extend({}, $.fn.yDialog.settings, options),
						ui, uiTitle, uiContent, uiButton, button, openNow;
					if (! $this.data('yDialog')) {
						ui = createUi();
						uiTitle = createUiTitle();
						uiContent = createUiContent();
						if (settings.buttons && settings.buttons.length > 0) {
							uiButton = createUiButton();
							$.each(settings.buttons, function (i, btn) {
								if ('object' !== $.type(btn) || ! btn.text) {
									return ;
								}
								if (! btn.callback && ! $.isFunction(btn.callback)) {
									btn.callback = function () {
										methods.close.apply($this);
									}
								}
								if (! btn.type) {
									btn.type = 'gray'
								}
								button = $('<a href="javascript:;"></a>')
									.addClass('btn-popup')
									.addClass('btn-popup-' + btn.type)
									.text(btn.text)
									.click(function () {
										btn.callback.apply($this);
									});
								uiButton.append(button);
							});
						}
						if ($('.mask').length < 1) {
							$('body').append($('<div>').addClass('mask'));
						}
						ui.on('click', '.btn-popup-close', function () {
							methods.close.apply($this);
						});
						openNow = settings.autoOpen;
					} else {
						ui = $this.data('yDialog');
						uiTitle = ui.find('.popup-hd').remove();
						uiContent = ui.find('.popup-con').remove();
						uiButton = ui.find('.pop-btns').remove();
						openNow = true;
					}

					uiTitle.find('.popup-tit').text(settings.title);
					ui.append(uiTitle);

					ui.css('width', settings.width);
					ui.css('height', $this.height() + 170);
					setPosition(ui);

					uiContent.append($this.show());
					ui.append(uiContent);

					if (uiButton) {
						ui.append(uiButton);
					}

					ui.appendTo($(settings.appendTo));

					$this.data('yDialog', ui);

					if (openNow) {
						methods.open.apply($this);
					}
				});
			},
			open: function () {
				return this.each(function() {
					var $this = $(this);
					$('.mask').show();
					$this.data('yDialog').show();
				});
			},
			hide: function () {
				$('.mask, .popup').hide();
				return this;
			},
			refresh: function () {
				return this.each(function() {
					var $this = $(this);
					$this.data('yDialog').css('height', $this.height() + 170);
					setPosition($this.data('yDialog'));
				});
			},
			close: function () {
				return this.each(function() {
					var $this = $(this);
					$('.mask').hide();
					$this.data('yDialog').hide();
				});
			},
			destroy: function () {
				return this.each(function() {
					var $this = $(this);
					$this.data('yDialog').remove();
					$this.removeData('yDialog');
					$this.hide().appendTo($('body'));
					$this.closest('.popup').remove();
				});
			}
		};

		$.fn.yDialog = function () {
			var method = arguments[0],
				args;
			if(methods[method]) {
				method = methods[method];
				args = slice.call(arguments, 1);
			} else {
				method = methods.init;
				args = slice.call(arguments, 0);
			}
			return method.apply(this, args);
		};

		// 默认配置
		$.fn.yDialog.settings = {
			appendTo: 'body',
			autoOpen: false,
			title: '提示信息',
			width: 600,
			buttons: []
		};
	})(jQuery);


	/**
	 * 表单弹框
	 */
	(function () {
		var methods = {
			init: function(title, width) {
				return this.each(function() {
					var $this = $(this),
						settings = $.extend({}, $.fn.yForm.settings);
					if (title) {
						settings.title = title;
					}
					if (width) {
						settings.width = width;
					}
					settings.buttons = [{
						text: '提交',
						type: 'red',
						callback: function () {
							if ($this.not('form')) {
								$this.find('form').submit();
							} else {
								$this.submit();
							}
						}
					}, {
						text: '取消'
					}];
					$this.data('yForm', $this.yDialog(settings));
				});
			}
		};
		$.fn.yForm = function () {
			var mth = arguments[0],
				method, args;
			if (mth) {
				if (methods[mth]) {
					method = methods[mth];
					args = slice.call(arguments, 1);
				} else if (-1 !== $.inArray(mth, ['open', 'hide', 'refresh', 'close', 'destroy'])) {
					method = this.yDialog;
					args = slice.call(arguments, 0);
				} else {
					method = methods.init;
					args = slice.call(arguments, 0);
				}
			} else {
				method = methods.init;
				args = slice.call(arguments, 0);
			}
			return method.apply(this, args);
		};

		// 默认配置
		$.fn.yForm.settings = {
			width: 600,
			title: '表单'
		};
	})(jQuery);


	/**
	 * 确认对话框
	 */
	(function () {
		$.extend({
			yConfirm: function (msg, title, ok) {
				var $yConfirm = $('.yConfirm');
				if ($yConfirm.length > 0) {
					$yConfirm.yDialog('destroy');
				} else {
					$yConfirm = $('<div class="yConfirm" style="height: 100px;">').addClass('form-con');
				}
				if (! ok) {
					ok = title;
					title = '';
				}
				var settings = {
					autoOpen: true,
					width: 460,
					title: '确认',
					buttons: [{
						text: '确认',
						type: 'red',
						callback: function () {
							ok.call(this);
						}
					}, {
						text: '取消'
					}]
				};
				if (title) {
					settings.title = title;
				}
				$yConfirm.html(msg).yDialog('hide').yDialog(settings);
			}
		});
	})(jQuery);


	/**
	 * 消息提示框
	 */
	(function () {
		$.extend({
			yMsg: function (msg, title) {
				var $yMsg = $('.yMsg');
				if ($yMsg.length > 0) {
					$yMsg.yDialog('destroy');
				} else {
					$yMsg = $('<div class="yMsg" style="height: 80px;">').addClass('form-con');
				}
				var settings = {
					buttons: [{text: '关闭'}],
					autoOpen: true,
					width: 460
				};
				if (title) {
					settings.title = title;
				}
				$yMsg.text(msg).yDialog('hide').yDialog(settings);
			},
			ySuccess: function (msg, title, options) {
				if (! title) {
					title = '操作成功';
				}
				$.yMsg(msg, title, options);
			},
			yError: function (msg, title, options) {
				if (! title) {
					title = '操作失败';
				}
				$.yMsg(msg, title, options);
			}
		});
	})(jQuery);



	/**
	 * ajax
	 */
	(function () {
		$.fn.extend({
			yAjax: function (options) {
				var $this = $(this);
				if ($this.yDisabled()) {
					return;
				}
				$this.yDisable();
				$.extend(options, {
					complete: function () {
						$this.yEnable();
					},
					dataType: 'json',
					method: 'post'
				});
				$.ajax.call($, options);
				return $this;
			}
		});
		// 设置全局ajax处理
		$(document).ajaxError(function (event, jqXHR) {
			if (200 !== jqXHR.status) {
				$.yError(jqXHR.status + ' ' + jqXHR.statusText, 'Request failed');
			} else {
				$.yError('Parse JSON failed', 'Request failed');
			}
		});
	})();

}(jQuery));