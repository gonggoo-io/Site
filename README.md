## '공구' 기획
공동구매라고 하면 보통 판매자가 상품을 등록하고, 일정 수량이 모이면 판매가 이루어지는 방식입니다.
하지만 제가 기숙사 학교에서 생활을 하며 친구들과 공구를 할 때는 쿠팡, 알리익스프레스 등 플랫폼에 올려진 물품을 공동구매 하는 방법으로 하게 되었습니다.
이 방법으로 공구를 하게 되면 살만할 친구들에게 카톡으로 보내고, 계좌도 보내며 1인당 가져가는 개수 등등 여러가지를 톡으로만 주고 받아야 했습니다.
<img src="https://github.com/user-attachments/assets/f26cc943-eb8f-41d9-808d-e4f95edb87b7" width="400"/>
이 불편함을 저는 서비스로 만들어서 좀 더 편하게 친구들에게 링크만으로 공유하고, 미리 작성해놓은 계좌 번호를 알림으로 보내면서 정말 간편하고 빠르게 공동구매가 이루어지도록 해결하고 싶어 공구를 개발하게 되었습니다.

공구를 사용하면 직장 동료, 학교 친구, 아파트 주민등 다양한 영역에서 물건을 공구를 하는 것을 목표로 개발하였습니다.

## 프로젝트 설계 (지도)
아뮤즈 홈페이지 중 채용 부분을 보며 지도 API 리소스 관련 사용한 경험이 있으면 우대한다는 것을 본 적이 있었습니다. 

이 사항을 프로젝트에 직접적으로 적용하고 싶었고, 공구 배송 위치를 알리는데 Kakao Map API를 사용하였습니다.
- 등록에서 공구 지역을 검색할 때 카카오맵 검색 API를 사용하여 설계하였습니다.
- 콘텐츠 영역에서 공구 지역을 사용자들께 보여줄 때 지도에 마커를 설정해 띄웠습니다. 

## 프로젝트 설계 (알림)
공구에 참여하거나, 취소, 공구가 성사되었을 때 입금 계좌 정보와 배송 정보를 유저에게 알리는데 알림을 사용했습니다.
공구를 하면서 알림은 무조건 필요한 방법이라고 생각했습니다. 하지만 앱이 아니였고, 웹앱 이였기에 푸쉬 알림은 서비스에 적절하지 않다고 생각했습니다.
방법을 찾던 도중 SSE라는 것을 알게되었고, 이 방법을 사용해 실시간 알림을 구현하였습니다. 

## 프로젝트 설계 (등록 og meta)
공구에 대한 내용을 등록하기 위해서는 초반에 타이틀, 설명 등 꽤 귀찮은 정보를 적어야 합니다.
이 귀찮음을 조금이나마 덜기 위해서 open graph라는 기술을 사용했습니다.
개발자 도구에서 source에 나와있는 각 내용에 대한 타이틀, 설명, 이미지 URL을 가져와서 띄우는 방식이였습니다. 

개발을 하면서 왠만한 쇼핑 사이트에서는 og meta가 작동했지만 테스트한 서비스 중 쿠팡, 지마켓, 옥션에서는 og meta 태그를 가져가능 것을 막아 놓아 직접 입력 하는 방식으로 구현하게 되었습니다. 
