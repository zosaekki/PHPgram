<div id="lData" data-toiuser="<?=$this->data->iuser?>"></div>
<div class="d-flex flex-column align-items-center">
    <div class="size_box_100"></div>
    <div class="w100p_mw614">
        <div class="d-flex flex-row">            
                <div class="d-flex flex-column justify-content-center me-3">
                    <a href="#" id="btnNewProfileModal" data-bs-toggle="modal" data-bs-target="#newProfileModal">
                        <div class="circleimg h150 w150 pointer feedwin">
                            <img class="profileimg" src='/static/img/profile/<?=$this->data->iuser?>/<?=$this->data->mainimg?>' onerror='this.error=null;this.src="/static/img/profile/defaultProfileImg.png"'>
                        </div>
                    </a>
                </div>
            <div class="flex-grow-1 d-flex flex-column justify-content-evenly">
                <div>
                    <?php $email = $this->data->email;
                        $emailArr = explode('@', $email);
                        print $emailArr[0];
                    ?>
                <!-- <?php
                    if($this->data->iuser === getIuser()) {
                        print "<button type='button' id='btnModProfile' class='btn btn-outline-secondary'>프로필 수정</button>";
                    } else if($this->data->youme === 0 && $this->data->meyou === 0) {
                        print "<button type='button' id='btnFollow' data-follow='0' class='btn btn-primary'>팔로우</button>";
                    } else if($this->data->youme === 0) {
                        print "<button type='button' id='btnFollow' data-follow='1' class='btn btn-ourline-secondary'>팔로우 취소</button>";
                    } else if($this->data->meyou === 0) {
                        print "<button type='button' id='btnFollow' data-follow='0' class='btn btn-primary'>맞팔로우 하기</button>";
                    }
                ?> -->
                <?php
                    if($this->data->iuser === getIuser()) {
                        echo "<button type = 'button' id='btnModProfile' class='btn btn-outline-secondary'>프로필 수정</button>";
                    } else {
                        $data_follow = 0;
                        $cls = "btn-primary";
                        $txt = "팔로우";

                        if($this->data->meyou === 1) {
                            $data_follow = 1;
                            $cls = "btn-outline-secondary";
                            $txt = "팔로우 취소";
                        } else if($this->data->youme === 1 && $this->data->meyou === 0) {
                            $txt = "맞팔로우 하기";
                        }
                        echo "<button type='button' id='btnFollow' data-youme='{$this->data->youme}' data-follow='{$data_follow}' class='btn {$cls}'>{$txt}</button>";
                    }
                ?>
                </div>
                <div class="d-flex flex-row">
                    <div class="flex-grow-1">게시글 <span><?=$this->data->feedCnt?></span></div>
                    <div class="flex-grow-1">팔로워 <span id="spanFollow"><?=$this->data->follower?></span></div>
                    <div class="flex-grow-1">팔로우 <span><?=$this->data->following?></span></div>
                </div>
                <div class="bold"><?=$this->data->nm?></div>
                <div><?=$this->data->cmt?></div>
            </div>
        </div>
        <div id="item_container"></div>
    </div>
    <div class="loading d-none"><img src="/static/img/loading.gif"></div>
</div>

<!-- 프로필 사진 변경 -->
<div class="modal fade" id="newProfileModal" tabindex="-1" aria-labelledby="newProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center" id="newFeedModalContent">
            <div class="modal-header d-block">
                <h5 class="modal-title text-center" id="newFeedModalLabel">프로필 사진 바꾸기</h5>
            </div>
            <div class="modal-body" id="id-modal-body">
                <span class="text-primary bg-white pointer">사진 업로드</span>
                <hr>
                <span id="delCurrentProfilePic" class="text-danger bg-white pointer">현재 사진 삭제</span>
                <hr>
                <span class="text-muted bg-white pointer" id="btnProfileImgModalClose" data-bs-dismiss="modal">취소</span>
            </div>
        </div>

        <form class="d-none">
            <input type="file" accept="image/*" name="imgs" multiple>
        </form>
    </div>
</div>