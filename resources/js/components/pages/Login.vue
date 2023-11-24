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
    エラー: string[]
} = reactive({
    メールアドレス: '',
    パスワード: '',
    エラー: []
})

const ログイン = async() => {
    await axios.post('/api/login', {
        mail: data.メールアドレス,
        password: data.パスワード
    }).then(() => {
        console.log("success");
        
    }).catch((e) => {
        console.log(e);
        
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
                    <div class="top_heading_center">ログイン</div>
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
                    />
                    <div class="content_foot_push">
                        <Button
                            label="ログイン"
                            color="primary"
                            plain
                            @click="ログイン"
                        />
                    </div>
                    <div class="content__foot--push">
                        <el-link @click="router.push('/register')">まだアカウントをお持ちでない方はこちら</el-link>
                    </div>
                </Stack>
            </Center>
        </template>
    </OneColumnTemplate>
</template>

<style>
.top_heading_center {
    text-align: center;
}

.content_foot_push {
    display: flex;
    justify-content: center;
}
</style>