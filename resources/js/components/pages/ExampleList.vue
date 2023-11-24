<script setup lang="ts">
import { onMounted, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { 例文型, 単語型 } from 'resources/js/types/word';
import axios from 'axios';
import OneColumnTemplate from '../templates/OneColumnTemplate.vue';
import Button from '../atom/Button.vue';
import Header from '../molecules/Header.vue';
import Center from '../layout/Center.vue'
import Accordion from '../atom/Accordion.vue';

const router = useRouter()

const data: {
    アカウントID: number
    単語リスト: 単語型[]
    例文リスト: 例文型[]
} = reactive({
    アカウントID: 7,
    単語リスト: [],
    例文リスト: []
})

const 単語一覧を取得 = async() => {
    await axios.post('/api/list/example', {
        account_id: data.アカウントID
    })
    .then((response) => {
        console.log(response.data);
        
        response.data.map((x: any) => {
            data.例文リスト.push({
                id: x.id,
                sentence: x.sentence,
                meaning: x.meaning,
                meaning_id: x.meaning_id
            })
        })
        data.単語リスト = response.data
    })
}

const 単語を追加 = () => {
    router.push('/add')
}

const 単語一覧に遷移 = () => {
    router.push('/list')
}

const 編集画面に遷移 = () => {
}

const 削除する = async() => {
}

onMounted(() => {
    単語一覧を取得()
})

</script>

<template>
    <OneColumnTemplate>
        <template v-slot:header>
            <Header/>
        </template>
        <template v-slot:content>
            <Center max="60ch">
                <div class="content__button">
                    <Button
                        label="単語を追加"
                        color="primary"
                        plain
                        @click="単語を追加"
                    />
                </div>
                <el-link @click="単語一覧に遷移">単語</el-link><span class="content__menu"> / 例文</span>
                <Accordion v-for="example of data.例文リスト">
                    <template v-slot:title>
                        <div>{{ example.sentence }}</div>
                    </template>
                    <template v-slot:menu>
                        <img class="content__card--image" src="../../assets/edit.png" alt="edit" @click="編集画面に遷移">
                        <span class="content__card--icon"><img class="content__card--image" src="../../assets/delete.png" alt="delete" @click="削除する"></span>
                    </template>
                    <template v-slot:content>
                        <div class="content__card--description">
                            {{ example.meaning }}
                        </div>
                    </template>
                </Accordion>
                <el-pagination layout="prev, pager, next" :total="50" />
            </Center>
        </template>
    </OneColumnTemplate>
</template>

<style>
.content__button {
    display: flex;
    justify-content: end;
}

.content__card {
    margin: 1rem 0;
}

.content__menu {
    font-size: small;
    color: rgb(191, 191, 191);
}

.content__card--inside {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.content__card--image {
    width: 1rem;
}

.content__card--icon {
    padding-left: 1rem;
}

.content__card--iconUpsideDown {
    transform: rotate(180deg);
}

.content__card--description {
    padding: 1rem 1rem 0rem 1rem;
    font-size: small;
    display: flex;
    column-gap:30px;
}
</style>