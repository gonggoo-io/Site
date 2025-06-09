<template>
  <div class="bg-white">
    <Header />
    
    <div class="py-12">
      <Container>
        <h1 class="text-3xl font-bold mb-8">개인정보 처리방침</h1>
        
        <div class="space-y-8">
          <section>
            <h2 class="text-xl font-semibold mb-4">1. 수집하는 개인정보 항목</h2>
            <p class="text-gray-600 mb-2">회원가입 시 수집하는 정보:</p>
            <ul class="list-disc list-inside text-gray-600 space-y-1 ml-4">
              <li>이름</li>
              <li>이메일 주소</li>
              <li>비밀번호</li>
            </ul>
          </section>

          <section>
            <h2 class="text-xl font-semibold mb-4">2. 개인정보의 수집 및 이용목적</h2>
            <p class="text-gray-600 mb-2">수집된 개인정보는 다음의 목적을 위해 이용됩니다:</p>
            <ul class="list-disc list-inside text-gray-600 space-y-1 ml-4">
              <li>회원 식별 및 가입의사 확인</li>
              <li>공동구매 서비스 제공</li>
              <li>고객 문의 및 불만 처리</li>
              <li>서비스 이용에 따른 본인확인</li>
            </ul>
          </section>

          <section>
            <h2 class="text-xl font-semibold mb-4">3. 개인정보의 보유 및 이용기간</h2>
            <p class="text-gray-600">
              회원의 개인정보는 원칙적으로 개인정보의 수집 및 이용목적이 달성되면 지체 없이 파기합니다. 
              단, 관계법령의 규정에 의하여 보존할 필요가 있는 경우 회사는 아래와 같이 관계법령에서 정한 일정한 기간 동안 회원정보를 보관합니다.
            </p>
          </section>

          <section>
            <h2 class="text-xl font-semibold mb-4">4. 개인정보의 파기절차 및 방법</h2>
            <p class="text-gray-600">
              회사는 원칙적으로 개인정보 수집 및 이용목적이 달성된 후에는 해당 정보를 지체 없이 파기합니다. 
              파기절차 및 방법은 다음과 같습니다:
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-1 ml-4 mt-2">
              <li>파기절차: 회원이 서비스 가입 등을 위해 입력한 정보는 목적이 달성된 후 별도의 DB로 옮겨져 내부 방침 및 기타 관련 법령에 의한 정보보호 사유에 따라 일정 기간 저장된 후 파기됩니다.</li>
              <li>파기방법: 전자적 파일 형태로 저장된 개인정보는 기록을 재생할 수 없는 기술적 방법을 사용하여 삭제합니다.</li>
            </ul>
          </section>

          <section>
            <h2 class="text-xl font-semibold mb-4">5. 개인정보 보호책임자</h2>
            <p class="text-gray-600">
              회사는 개인정보 처리에 관한 업무를 총괄해서 책임지고, 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제 등을 위하여 아래와 같이 개인정보 보호책임자를 지정하고 있습니다.
            </p>
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
              <p class="text-gray-600">개인정보 보호책임자: 신희성</p>
              <p class="text-gray-600">이메일: shinhuiseong07@gmail.com</p>
            </div>
          </section>
        </div>
      </Container>
    </div>

    <Footer />
  </div>
</template>

<script>
import Header from '../../js/Pages/components/Header.vue';
import Footer from '../Pages/components/Footer.vue';
import Container from '../Pages/components/Container.vue';

export default {
  name: 'PrivacyPage',
  components: {
    Header,
    Footer,
    Container
  }
};
</script> 