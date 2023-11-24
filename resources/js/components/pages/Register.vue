<script setup lang="ts">
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Input from '../atom/Input.vue';
import Button from '../atom/Button.vue';
import Stack from '../layout/Stack.vue'
import Center from '../layout/Center.vue'
import Header from '../molecules/Header.vue';
import Notification from '../molecules/Notification.vue';
import OneColumnTemplate from '../templates/OneColumnTemplate.vue';

const router = useRouter()

const data: {
    メールアドレス: string
    パスワード: string
    確認パスワード: string
    エラー: string[]
} = reactive({
    メールアドレス: '',
    パスワード: '',
    確認パスワード: '',
    エラー: []
})

const 初期化処理 = () => {
    data.メールアドレス = ''
    data.パスワード = ''
    data.確認パスワード = ''
}

const 会員登録 = async() => {
    data.エラー = []
    await axios.post('/api/register', {
        mail: data.メールアドレス,
        password: data.パスワード,
        password_confirmation: data.確認パスワード
    }).then(() => {
        初期化処理()
        router.push('/login')
    }).catch((e) => {
        data.エラー = e.response.data.errors.mail.concat(e.response.data.errors.password)
    })
}

</script>

<template>
    <OneColumnTemplate>
        <template v-slot:header>
            <Header />
        </template>
        <template v-slot:content>
            <Center max="40ch">
                <Stack>
                    <div class="top__heading--center">会員登録</div>
                    <Notification v-if="data.エラー.length > 0">
                        <template v-for="エラーメッセージ of data.エラー">
                            <div>{{ エラーメッセージ }}</div>
                        </template>
                    </Notification>
                    <Input
                        v-model="data.メールアドレス"
                        placeholder="abcde@ABC.co.jp"
                        label="メールアドレス"
                    />
                    <Input
                        v-model="data.パスワード"
                        label="パスワード"
                        type="password"
                        name="password"
                    />
                    <Input
                        v-model="data.確認パスワード"
                        label="確認用パスワード"
                        type="password"
                        name="password_confirmation"
                    />
                    <div class="content__foot--push">
                        <Button
                            label="登録"
                            color="primary"
                            plain
                            @click="会員登録"
                        />
                    </div>
                    <div class="content__foot--push">
                        <el-link @click="router.push('/login')">既にアカウントをお持ちの方はこちら</el-link>
                    </div>
                </Stack>
            </Center>
        </template>
    </OneColumnTemplate>
</template>

<style>
.top__heading--center {
    text-align: center;
}

.content__foot--push {
    display: flex;
    justify-content: center;
}
</style>