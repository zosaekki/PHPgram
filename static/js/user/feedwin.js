if(feedObj) {
    const url = new URL(location.href);
    feedObj.iuser = parseInt(url.searchParams.get('iuser'));
    feedObj.getFeedUrl = '/user/feed';
    feedObj.getFeedList();
}


/*
function getFeedList() {
    if(!feedObj) { return; }
    feedObj.showLoading();            
    const param = {
        page: feedObj.currentPage++,
        iuser: url.searchParams.get('iuser')
    }
    fetch('/user/feed' + encodeQueryString(param))
    .then(res => res.json())
    .then(list => {                
        feedObj.makeFeedList(list);                
    })
    .catch(e => {
        console.error(e);
        feedObj.hideLoading();
    });
}
getFeedList();
*/


(function() {
    const lData = document.querySelector('#lData');
    const btnFollow = document.querySelector('#btnFollow');
    const btnDelCurrentProfilePic = document.querySelector('#delCurrentProfilePic');
    const btnProfileImgModalClose = document.querySelector('#btnProfileImgModalClose');  
    const btnUpdCurrentProfilePic = document.querySelector('#updCurrentProfilePic');
    const formProfileImg = document.querySelector('#modalProfileImg');


    const spanFollow = document.querySelector('#spanFollow');
    let followNum = parseInt(spanFollow.innerText);

    if(btnFollow) {
        btnFollow.addEventListener('click', function() {
            const param = {
                toiuser: parseInt(lData.dataset.toiuser)
            };
            console.log(param);
            const follow = btnFollow.dataset.follow;
            console.log('follow : ' + follow);
            const followUrl = '/user/follow';
            switch(follow) {
                case '1': //팔로우 취소
                    fetch(followUrl + encodeQueryString(param), {method: 'DELETE'})
                    .then(res => res.json())
                    .then(res => {
                        if(res.result) {
                            btnFollow.dataset.follow = '0';
                            btnFollow.classList.remove('btn-outline-secondary');
                            btnFollow.classList.add('btn-primary');
                            if(btnFollow.dataset.youme === '1') {
                                btnFollow.innerText = '맞팔로우';
                            } else {
                                btnFollow.innerText = '팔로우';
                            }
                            followNum = followNum - 1;
                            spanFollow.innerText = followNum;
                        }
                    });
                    break;
                case '0': //팔로우 등록
                fetch(followUrl, {
                    method: "POST",
                    body: JSON.stringify(param)
                })
                .then(res => res.json())
                .then(res => {
                    if(res.result) {
                        btnFollow.dataset.follow = '1';
                        btnFollow.classList.remove('btn-primary');
                        btnFollow.classList.add('btn-outline-secondary');
                        btnFollow.innerText = '팔로우 취소';
                        followNum = followNum + 1;
                        spanFollow.innerText = followNum;
                    }
                });
                    break;
            }
        });
    }

    if(btnDelCurrentProfilePic) {
        btnDelCurrentProfilePic.addEventListener('click', e => {
            fetch('/user/profile', {method: 'DELETE'})
            .then(res => res.json())
            .then(res => {
                if(res.result) {
                    const profileImgList = document.querySelectorAll('.profileimg');
                    profileImgList.forEach(item => {
                        item.src = '/static/img/profile/defaultProfileImg.png';
                    })
                    btnProfileImgModalClose.click();
                }
            })
        })
    }

    if(btnUpdCurrentProfilePic) {
        btnUpdCurrentProfilePic.addEventListener('click', e => {
            formProfileImg.imgs.click();
        })

        formProfileImg.imgs.addEventListener('change', e => {
            console.log(e.target.files.length);
            if(e.target.files.length){
                const fData = new FormData();
                fData.append('profileImg', e.target.files[0]);
                fetch('/user/profile', {
                    method: 'post',
                    body: fData
                })
                .then(res => res.json())
                .then(res => {
                    if(res.result){
                        const gData = document.querySelector('#gData');
                        const profileImgList = document.querySelectorAll('.profileimg');
                        profileImgList.forEach(item => {
                            item.src = `/static/img/profile/${gData.dataset.loginiuser}/${res.fileNm}`;
                        });
                        btnProfileImgModalClose.click();
                    }
                });
            }
        });
    }
})();