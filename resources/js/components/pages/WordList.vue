<script setup lang="ts">
import { onMounted, reactive, watchEffect } from 'vue';
import { useRouter } from 'vue-router';
import { 単語型 } from 'resources/js/types/word';
import axios from 'axios';
import OneColumnTemplate from '@/components/templates/OneColumnTemplate.vue';
import Button from '@/components/atom/Button.vue';
import Header from '@/components/molecules/Header.vue';
import Center from '@/components/layout/Center.vue'
import Stack from '@/components/layout/Stack.vue'
import Cluster from '@/components/layout/Cluster.vue';
import Accordion from '@/components/atom/Accordion.vue';
import PopupModal from '@/components/molecules/PopupModal.vue'
import SearchBox from '@/components/molecules/SearchBox.vue';
import Select from '@/components/atom/Select.vue';
import Notification from '../molecules/Notification.vue';
import { EditPen, Delete } from '@element-plus/icons-vue'
import Icon from '@/components/atom/Icon.vue';

const router = useRouter()

const data: {
    アカウントID: number
    単語リスト: 単語型[]
    削除モーダルフラグ: boolean
    削除する単語id: string
    並び順: string
    エラー: string[]
    現在のページ: number
} = reactive({
    アカウントID: 7,
    単語リスト: [],
    削除モーダルフラグ: false,
    削除する単語id: '',
    並び順: '',
    エラー: [],
    現在のページ: 1
})

const 選択肢 = [
    {
        label: '語順',
        value: '語順'
    },
    {
        label: '登録の新しい順',
        value: '登録の新しい順'
    }
]

const 単語一覧を取得 = async() => {
    await axios.post('/api/list', {
        account_id: data.アカウントID
    })
    .then((response) => {
        console.log(response);
        data.単語リスト = response.data
    })
}

const 単語を検索する = async(検索文字列: string) => {
    await axios.post('/api/word/search', {
        account_id: data.アカウントID,
        word: 検索文字列
    })
    .then((response) => {
        data.単語リスト = response.data
        
        if(data.単語リスト.length === 0) {
            data.エラー.push('当てはまる単語は登録されていません')
        }
    })
    .catch((e) => {
        console.log(e);
        
    })
}

const 単語を追加 = () => {
    router.push('/add')
}

const 編集画面に遷移 = (単語id: string) => {
    router.push(`/word/${単語id}`)
}

const 削除する = async() => {
    await axios.post('/api/word/delete', {
        account_id: data.アカウントID,
        word_id: data.削除する単語id
    })
    .then(() => {
        単語一覧を取得()
    })
    .catch((e) => {
        console.log(e);
        
    })
}

const 削除モーダルを表示 = (単語id: string) => {
    data.削除モーダルフラグ = true
    data.削除する単語id = 単語id
}

const 例文画面に遷移 = (id: string) => {
    console.log(id);
    
}

const 例文一覧に遷移 = () => {
    router.push('/list/example')
}

const ページを移動する = (ページ: number) => {
    console.log(ページ);
    // console.log(data.現在のページ);
    
}

watchEffect(() => {
    if(data.並び順 === '語順') {
        console.log(data.並び順);
    }
    if(data.並び順 === '登録の新しい順') {

    }
    
})

// const 認証 = (async() => {
//     await axios.get('/api/user')
//     .then((res) => {
//         console.log(res)
//     })
//     .catch((err) => {
//         console.log(err)
//     })
// })

onMounted( async() => {
    await axios.get('sanctum/csrf-cookie')
    await 単語一覧を取得()
    // 認証()
})

</script>

<template>
    <OneColumnTemplate>
        <template v-slot:header>
            <Header/>
        </template>
        <template v-slot:content>
            <Center max="60ch">
                <Stack>
                    <Notification padding="small" v-if="data.エラー.length > 0">
                        <template v-for="エラーメッセージ of data.エラー">
                            <div>{{ エラーメッセージ }}</div>
                        </template>
                    </Notification>
                    <SearchBox
                        placeholder="単語を入力してください"
                        @click="単語を検索する"
                    />
                    <div class="content__button">
                        <Button
                            label="単語を追加"
                            color="primary"
                            plain
                            @click="単語を追加"
                        />
                    </div>
                </Stack>
                <Cluster>
                    <div>
                        <span class="content__menu">単語 / </span><el-link @click="例文一覧に遷移">例文</el-link>
                    </div>
                    <Select
                        :option="選択肢"
                        v-model="data.並び順"
                    />
                </Cluster>
                <Accordion v-for="word in data.単語リスト" :no-show="word.meanings.length === 0">
                    <template v-slot:title>
                        <div>{{ word.name }}</div>
                    </template>
                    <template v-slot:menu>
                            <Icon class="card__icon" @click="編集画面に遷移(word.id)"><EditPen /></Icon>
                            <Icon @click="削除モーダルを表示(word.id)"><Delete /></Icon>
                    </template>
                    <template v-slot:content>
                        <div
                            v-if="word.meanings.length > 0"
                            v-for="意味, index of word.meanings"
                        >
                            <Stack>
                                <div class="content__card--description">
                                    <Stack space="0.5rem">
                                        <div>{{ `意味 - ${index + 1}` }}</div>
                                        <div>{{ 意味.wordMeaning }}</div>
                                    </Stack>
                                </div>
                                <div v-if="意味.examples.length > 0">
                                    <Stack>
                                        <div v-for="example, index of 意味.examples" class="card__examples-background">
                                            <Stack space="0.5rem">
                                                <div>{{ `例文 - ${index + 1}` }}</div>
                                                <div>
                                                    <div>{{ example.sentence }}</div>
                                                    <div>{{ example.meaning }}</div>
                                                </div>
                                            </Stack>
                                        </div>
                                    </Stack>
                                </div>
                            </Stack>
                        </div>
                    </template>
                </Accordion>
                <el-pagination v-model:current-page="data.現在のページ" layout="prev, pager, next" :total="50" @current-change="ページを移動する" />
            </Center>
        </template>
    </OneColumnTemplate>
    <PopupModal
        :isActive="data.削除モーダルフラグ"
        rightColor="danger"
        rightLabel="削除"
        @success="削除する"
        @close="data.削除モーダルフラグ=false"
    >
        この単語を削除してよろしいですか？
    </PopupModal>
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

.card__icon {
    padding-right: 0.5rem;
}

.content__card--iconUpsideDown {
    transform: rotate(180deg);
}

.content__card--description {
    padding: 1rem 1rem 0rem 1rem;
    font-size: small;
}

.card__examples-background {
    background-color: rgb(232, 249, 255);
    font-size: small;
    padding: 1rem;
    border-radius: 0.3rem;
    display: flex;
    flex-direction: column;
    row-gap: 1rem;
}

</style>