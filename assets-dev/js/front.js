import "../scss/front.scss";

if (typeof elementorFrontend !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', function () {
        let editMode = false;
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/apfe_widget.default',
            function ($scope) {
                if (elementorFrontend.isEditMode()) {
                    editMode = true;
                }
                let shuffleEnable = false;
                let loopEnable = true;
                let playedHistory = [];
                let playingStatus = false;
                const playerContainer = $scope.find('.apfe-player-container').eq(0);
                const playerElement = $scope.find('.apfe-player').eq(0);
                const playlistItems = $scope.find('.apfe-playlist li');
                if (playlistItems.length === 0) {
                    return;
                }
                const icons = {
                    play: $scope.find('.apfe-icons-play').eq(0).html(),
                    pause: $scope.find('.apfe-icons-pause').eq(0).html(),
                    prev: $scope.find('.apfe-icons-prev').eq(0).html(),
                    next: $scope.find('.apfe-icons-next').eq(0).html(),
                    volume: $scope.find('.apfe-icons-volume').eq(0).html(),
                    mute: $scope.find('.apfe-icons-mute').eq(0).html(),
                    shuffle: $scope.find('.apfe-icons-shuffle').eq(0).html(),
                    loop: $scope.find('.apfe-icons-repeat').eq(0).html(),
                };
                const allowControl = playerContainer.attr('data-allow-control') === '1';
                const addShuffle = allowControl && playerContainer.attr('data-add-shuffle') === '1';
                const addLoop = allowControl && playerContainer.attr('data-add-loop') === '1';
                const showVolume = playerContainer.attr('data-show-volume') === '1';
                const showNext = allowControl && playerContainer.attr('data-show-next-prev') === '1';
                let shuffleHtml = `<span class="apfe-shuffle" title="Shuffle">${icons.shuffle}</span>`;
                let loopHtml = `<span class="apfe-loop active" title="Loop">${icons.loop}</span>`;
                let hasPlayed = false;
                let durations = [];
                let totalDuration = 0;
                playlistItems.each(function (i, val) {
                    let trackDuration = parseInt(val.getAttribute('data-duration'));
                    durations[i] = trackDuration;
                    totalDuration += trackDuration;
                });
                const symmetric = playerContainer.attr('data-symmetric') === '1';
                const centered = playerContainer.attr('data-centered') === '1';
                const sliderTop = playerContainer.attr('data-slider-top') === '1';
                const showSlider = playerContainer.attr('data-show-slider') === '1';
                const playingSection = playerContainer.attr('data-playing-section') === '1';
                const playingThumb = playerContainer.attr('data-playing-section-thumb') === '1';
                const playingHeader = playingSection ? playerContainer.attr('data-playing-header') : '';
                const changeTrack = playerContainer.attr('data-change-track') === '1';
                const startedPoint = parseInt(playerContainer.attr('data-start-point')) * 1000;
                let startTrack = 0;
                let playingPosition = 0;
                let calcRadioTime = function () {
                    if (changeTrack) {
                        const now = new Date().getTime();
                        let diffTime = now - startedPoint;
                        if (diffTime < 0) {
                            diffTime = totalDuration - diffTime;
                        }
                        const rounds = Math.ceil(diffTime / totalDuration);
                        let remaining = diffTime % totalDuration;
                        let stoppedIndex = 0;
                        for (let i = 0; i < durations.length; i++) {
                            if (remaining < 0) {
                                break;
                            }
                            stoppedIndex = i;
                            remaining -= durations[i];
                        }
                        startTrack = stoppedIndex;
                        playingPosition = Math.round((remaining + durations[stoppedIndex]) / 1000);
                    }
                }
                calcRadioTime();

                let instanceId = playerContainer.attr('data-widget-id');
                let currentIndex = startTrack;
                let controlsHtml = '';
                let features = [];
                const controlVolume = `<span class="apfe-volume" title="Volume">${icons.volume}</span>`;
                if (allowControl) {
                    controlsHtml = `<div class="apfe-custom-control"><span class="apfe-prev" title="Prev">${icons.prev}</span><span class="apfe-play-pause">${icons.play}</span><span class="apfe-next" title="Next">${icons.next}</span></div>`;
                    if (showSlider) {
                        if (showVolume) {
                            features = [
                                'current',
                                'progress',
                                'duration',
                                'volume',
                            ];
                        } else {
                            features = [
                                'current',
                                'progress',
                                'duration',
                            ];
                        }
                    } else {
                        if (showVolume) {
                            features = [
                                'current',
                                'duration',
                                'volume',
                            ];
                        } else {
                            features = [
                                'current',
                                'duration',
                            ];
                        }
                    }
                } else {
                    controlsHtml = `<div class="apfe-custom-control"><span class="apfe-play-pause">${icons.play}</span></div>`;
                    if (showVolume) {
                        features = [
                            'current',
                            'duration',
                            'volume',
                        ];
                    } else {
                        features = [
                            'current',
                            'duration',
                        ];
                    }
                }

                let player = playerElement.mediaelementplayer({
                    "features": features,
                    audioWidth: '100%',
                    success: function (media, originalNode, instance) {
                        playerContainer.find(".mejs-volume-button, .mejs-horizontal-volume-slider, .mejs-vertical-volume-slider").wrapAll('<div class="apfe-volume-control" />');
                        playerContainer.find(".mejs-time, .mejs-time-rail").wrapAll('<div class="apfe-duration-control" />');
                        playerContainer.find(".apfe-duration-control, .apfe-volume-control").wrapAll('<div class="apfe-other-control" />');
                        media.addEventListener('ended', playNext);
                        playerContainer.find('.mejs-controls').eq(0).prepend(controlsHtml);
                        playerContainer.find('.mejs-volume-button').eq(0).prepend(controlVolume);
                        if (sliderTop) {
                            playerContainer.find('.mejs-time.mejs-duration-container').eq(0).before('<span class="apfe-out-of">/</span>');
                        } else if (!showSlider || !allowControl) {
                            playerContainer.find('.mejs-time span').eq(1).remove();
                            playerContainer.find('.mejs-time span').eq(0).after('<span class="apfe-out-of">/</span>');
                        }
                        if (allowControl && addShuffle) {
                            if (symmetric) {
                                playerContainer.find('.apfe-custom-control').eq(0).prepend(shuffleHtml);
                            } else {
                                playerContainer.find('.apfe-custom-control').eq(0).append(shuffleHtml);
                            }
                            const shuffle = playerContainer.find('.apfe-shuffle').eq(0);
                            shuffle.on('click', function (e) {
                                jQuery(this).toggleClass('active');
                                shuffleEnable = !shuffleEnable;
                            });
                        }
                        if (allowControl && addLoop) {
                            playerContainer.find('.apfe-custom-control').eq(0).append(loopHtml);
                            const loop = playerContainer.find('.apfe-loop').eq(0);
                            loop.on('click', function (e) {
                                jQuery(this).toggleClass('active');
                                loopEnable = !loopEnable;
                            });
                        }
                        const playPause = playerContainer.find('.apfe-play-pause').eq(0);
                        const volume = playerContainer.find('.apfe-volume').eq(0);
                        if (playPause) {
                            playPause.on('click', function (e) {
                                playPause.find('i, svg').hide(200);
                                if (media.paused) {
                                    if (!allowControl && changeTrack) {
                                        calcRadioTime();

                                        playTrack(startTrack, playingPosition);
                                    } else {
                                        media.play();
                                    }
                                    jQuery('.apfe-player-container').each(function () {
                                        jQuery(this).find('.apfe-play-pause').html(jQuery(this).find('.apfe-icons-play').html());
                                    });

                                    playPause.html(icons.pause);
                                } else {
                                    media.pause();
                                    if (media.paused) {
                                        playPause.html(icons.play);
                                    }
                                }
                            });
                        }
                        if (volume) {
                            volume.on('click', function (e) {
                                if (media.muted) {
                                    media.muted = false;
                                    volume.html(icons.volume);
                                } else {
                                    media.muted = true;
                                    volume.html(icons.mute);
                                }
                            });
                        }
                        const controlPrev = playerContainer.find('.apfe-prev').eq(0);
                        if (controlPrev && allowControl) {
                            controlPrev.on('click', playPrev);
                        }
                        const controlNext = playerContainer.find('.apfe-next').eq(0);
                        if (controlNext && allowControl) {
                            controlNext.on('click', playNext);
                        }
                        if (playingSection) {
                            const playingHtml = `
                            <div class="apfe-playing-section">
                               <div class="apfe-playing-img"></div>
                               <div class="apfe-playing-title">
                                    <div class="apfe-playing-header">${playingHeader}</div>
                                    <p class="apfe-playing-track"></p>
                               </div>
                            </div>`;
                            playerContainer.find('.mejs-controls').eq(0).prepend(playingHtml);
                        }
                    }
                });
                player = player[0].player;

                function playTrack(index, from = 0)
                {
                    if (playlistItems === undefined) {
                        return;
                    }
                    index = isNaN(index) ? 0 : index;
                    from = isNaN(from) ? 0 : from;
                    playlistItems.each(function () {
                        jQuery(this).removeClass('active')
                    });
                    const selected = playlistItems.eq(index);
                    playedHistory.push(index);
                    selected.addClass('active');
                    const cover = playerContainer.find('.apfe-cover-card');
                    if (cover.length > 0) {
                        cover.eq(0).html(selected.find('.apfe-track-meta').eq(0).html());
                    }
                    const imgs = selected.find('img');
                    const bg = playerContainer.find('.apfe-play-card-bg');
                    if (bg.length > 0 && !bg.eq(0).hasClass('apfe-play-card-bg-fixed')) {
                        bg.eq(0).css("background-image", imgs.length > 0 ? 'url(' + imgs.eq(0).attr("src") + ')' : '');
                    }
                    if (playingThumb) {
                        const playingSourceImg = selected.find('.apfe-track-head img').eq(0).prop('outerHTML');
                        console.log(playingSourceImg);
                        playerContainer.find('.apfe-playing-img').eq(0).html(playingSourceImg);
                    }
                    const playingSourceTitle = selected.find('.apfe-track-title').eq(0).text();
                    playerContainer.find('.apfe-playing-track').eq(0).html(playingSourceTitle);
                    const src = selected.attr('data-src');
                    if (!hasPlayed) {
                        from = playingPosition;
                    }
                    player.setSrc(src);
                    player.setCurrentTime(from);
                    if (!hasPlayed && editMode) {
                        hasPlayed = true;
                        return;
                    }
                    player.play();
                    if (!player.paused) {
                        jQuery('.apfe-player-container').each(function () {
                            jQuery(this).find('.apfe-play-pause').html(jQuery(this).find('.apfe-icons-play').html());
                        });
                        const playPause = playerContainer.find('.apfe-play-pause').eq(0);
                        playPause.html(icons.pause);
                        if (!hasPlayed) {
                            //scrollTo(playerContainer.querySelectorAll('.apfe-playlist li.active')[0]);
                        }
                    }
                    hasPlayed = true;
                    playingStatus = !playingStatus;
                    currentIndex = index;
                }

                function playNext()
                {
                    if (allowControl && addShuffle && shuffleEnable) {
                        let nextIndex = Math.floor(Math.random() * (playlistItems.length - 1));
                        if (currentIndex <= nextIndex) {
                            nextIndex += 1;
                        }
                        playTrack(nextIndex);
                    } else {
                        if (allowControl && addLoop && !loopEnable) {
                            if (currentIndex + 1 <= playlistItems.length - 1) {
                                const nextIndex = (currentIndex + 1);
                                playTrack(nextIndex);
                            }
                        } else {
                            const nextIndex = (currentIndex + 1) % playlistItems.length;
                            playTrack(nextIndex);
                        }
                    }
                }

                function playPrev()
                {
                    playedHistory.pop();
                    let lastPlayed = playedHistory.pop();
                    if (allowControl && addShuffle && shuffleEnable) {
                        if (playedHistory.length > 0) {
                            const prevIndex = lastPlayed;
                            playTrack(prevIndex);
                        } else {
                            let prevIndex = Math.floor(Math.random() * (playlistItems.length - 1));
                            if (currentIndex <= prevIndex) {
                                prevIndex += 1;
                            }
                            playTrack(prevIndex);
                        }

                    } else {
                        if (allowControl && addLoop && !loopEnable) {
                            if (currentIndex - 1 < 0) {
                                const prevIndex = 0;
                                playTrack(prevIndex);
                            }
                        } else {
                            const prevIndex = (currentIndex - 1 + playlistItems.length) % playlistItems.length;
                            playTrack(prevIndex);
                        }
                    }

                }

                playlistItems.each((index, li) => {
                    li.addEventListener('click', function () {
                        if (allowControl) {
                            playTrack(index);
                            const playPause = playerContainer.find('apfe-play-pause').eq(0);
                            playPause.html(icons.pause);
                        }
                    });
                });

                if (playlistItems.length > 0) {
                    playTrack(startTrack, playingPosition);
                }
            });
    });
}
