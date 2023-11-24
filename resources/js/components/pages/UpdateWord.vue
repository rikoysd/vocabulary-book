<script setup lang="ts">
import { onMounted, reactive } from 'vue';
import { uuid } from 'vue-uuid'
import { 単語型, 意味型 } from '../../../../resources/js/types/word'
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import Button from '@/components/atom/Button.vue';
import Input from '@/components/atom/Input.vue';
import Textarea from '@/components/atom/Textarea.vue';
import Header from '@/components/molecules/Header.vue';
import PopupModal from '@/components/molecules/PopupModal.vue'
import Stack from '@/components/layout/Stack.vue'
import Center from '@/components/layout/Center.vue'
import Cluster from '@/components/layout/Cluster.vue';
import OneColumnTemplate from '@/components/templates/OneColumnTemplate.vue';
import { Delete } from '@element-plus/icons-vue'

const router = useRouter()
const route = useRoute()

const data: {
    単語: 単語型
    意味: 意味型
    追加用の意味: 意味型
    アカウントID: number
    編集対象の意味id: string
    意味の削除モーダルフラグ: boolean
    例文の削除モーダルフラグ: boolean
    削除する意味id: string
    削除する例文id: string
} = reactive({
    単語: {
        id: '',
        name: '',
        meanings: []
    },
    意味: {
        id: '',
        wordMeaning: '',
        examples: [],
        vocabulary_id: ''
    },
    追加用の意味: {
        id: '',
        wordMeaning: '',
        examples: [],
        vocabulary_id: ''
    },
    アカウントID: 7,
    編集対象の意味id: '',
    意味の削除モーダルフラグ: false,
    例文の削除モーダルフラグ: false,
    削除する意味id: '',
    削除する例文id: ''
})

const 初期化処理 = () => {
    data.編集対象の意味id = ''
    data.意味 = {
        id: '',
        wordMeaning: '',
        examples: [],
        vocabulary_id: ''
    }
}

const 単語を取得 = async() => {
    await axios.post('/api/word', {
        account_id: data.アカウントID,
        vocabulary_id: route.path.split('/')[2]
    })
    .then((response) => {
        data.単語 = response.data[0]
    })
}

const 編集する = (意味: 意味型) => {
    data.編集対象の意味id = 意味.id
    data.意味 = {
        id: 意味.id,
        wordMeaning: 意味.wordMeaning,
        examples: 意味.examples,
        vocabulary_id: ''
    }
}

const 意味の削除モーダルを表示 = (意味id: string) => {
    data.削除する意味id = 意味id
    data.意味の削除モーダルフラグ = true
}

const 例文の削除モーダルを表示 = (例文id: string) => {
    data.削除する例文id = 例文id
    data.例文の削除モーダルフラグ = true
}

const 意味を削除する = async() => {
    await axios.post('/api/word/delete/meaning', {
        id: data.削除する意味id
    }).then((response) => {
        単語を取得()
    }).catch((e) => {
        console.log(e)
    })
}

const 例文を削除する = async() => {
    await axios.post('/api/word/delete/example', {
        id: data.削除する例文id
    }).then((response) => {
        単語を取得()
    }).catch((e) => {
        console.log(e)
    })
}

const 意味を追加 = () => {
    data.追加用の意味 = {
        id: uuid.v1(),
        wordMeaning: '',
        examples: [],
        vocabulary_id: ''
    }
}

const キャンセル = () => {
    初期化処理()
}

const 意味の追加をキャンセル = () => {
    data.追加用の意味 = {
        id: '',
        wordMeaning: '',
        examples: [],
        vocabulary_id: ''
    }
}

const 意味を保存 = () => {
    const 意味 = data.単語.meanings.filter(x => x.id === data.意味.id)[0]
    意味.wordMeaning = data.意味.wordMeaning
    初期化処理()
}

const 例文を追加 = () => {
    data.意味.examples.push(
        {
            id: uuid.v1(),
            sentence: '',
            meaning: '',
            meaning_id: ''
        }
    )
}

const 意味を確定 = () => {
    data.追加用の意味.vocabulary_id = data.単語.id
    data.単語.meanings.push(data.追加用の意味)
    data.追加用の意味 = {
        id: '',
        wordMeaning: '',
        examples: [],
        vocabulary_id: ''
    }
}

const 単語を更新 = async() => {
    await axios.post('/api/word/update', {
        id: data.単語.id,
        name: data.単語.name,
        account_id: 7,
        meanings: data.単語.meanings
    }).then((response) => {
        router.push('/list')
    }).catch((e) => {
        console.log(e);
    })
}

const 一覧画面に戻る = () => {
    router.push('/list')
}

onMounted(() => {
    単語を取得()
})

</script>

<template>
    <OneColumnTemplate>
        <template v-slot:header>
            <Header />
        </template>
        <template v-slot:content>
            <Center max="50ch">
                <Stack>
                    <Input
                        v-model="data.単語.name"
                        label="単語名"
                    />
                    <div class="content__button">
                        <Button
                            label="意味を追加"
                            color="primary"
                            plain
                            @click="意味を追加"
                        />
                    </div>
                    <template
                        v-if="data.単語.meanings.length > 0"
                        v-for="meaning, index of data.単語.meanings"
                    >
                        <div class="content__form">
                            <!-- 入力した意味 -->
                            <Stack space="0.5rem" v-if="data.編集対象の意味id !== meaning.id">
                                <Cluster justify="space-between" align="center">
                                    <Stack space="0.3rem">
                                        <div>{{ `意味 - ${index + 1}` }}</div>
                                        <div>{{ meaning.wordMeaning }}</div>
                                    </Stack>
                                    <div>
                                        <Button
                                            label="削除"
                                            color="danger"
                                            plain
                                            @click="意味の削除モーダルを表示(meaning.id)"
                                        />
                                        <Button
                                            label="編集"
                                            color="success"
                                            plain
                                            :disabled="data.編集対象の意味id === meaning.id"
                                            @click="編集する(meaning)"
                                        />
                                    </div>
                                </Cluster>
                                <template v-for="例文, index of meaning.examples">
                                    <div class="content__list">
                                        <Cluster justify="space-between" align="center">
                                            <Stack space="0.3rem">
                                                <div>{{ `例文 - ${index + 1}` }}</div>
                                                <div>{{ 例文.sentence }}</div>
                                                <div>{{ 例文.meaning }}</div>
                                            </Stack>
                                            <Delete
                                                class="button__delete"
                                                style="width: 1em; height: 1em; margin-right: 8px"
                                                @click="例文の削除モーダルを表示(例文.id)"
                                            />
                                        </Cluster>
                                    </div>
                                </template>
                            </Stack>
                            <!-- 編集の入力フォーム -->
                            <div class="meaning__background" v-if="data.編集対象の意味id !== '' && data.編集対象の意味id === meaning.id">
                                <Stack>
                                    <Input
                                        v-model="data.意味.wordMeaning"
                                        label="意味"
                                    />
                                    <div class="content__button">
                                        <Button
                                            label="例文を追加"
                                            color="primary"
                                            plain
                                            @click="例文を追加"
                                        />
                                    </div>
                                    <template
                                        v-if="data.意味.examples.length > 0"
                                        v-for="example of data.意味.examples"
                                    >
                                        <Textarea
                                            v-model="example.sentence"
                                            label="例文 - 表"
                                        />
                                        <Textarea
                                            v-model="example.meaning"
                                            label="例文 - 裏"
                                        />
                                    </template>
                                    <div class="content__button">
                                        <Button
                                            label="キャンセル"
                                            plain
                                            @click="キャンセル"
                                        />
                                        <Button
                                            label="保存"
                                            color="primary"
                                            plain
                                            :disabled="data.意味.wordMeaning === ''"
                                            @click="意味を保存"
                                        />
                                    </div>
                                </Stack>
                            </div>
                        </div>
                    </template>
                    <!-- 追加の入力フォーム -->
                    <template v-if="data.追加用の意味.id !== ''">
                        <div class="meaning__background">
                            <Stack>
                                <Input
                                    v-model="data.追加用の意味.wordMeaning"
                                    label="意味"
                                />
                                <div class="content__button">
                                    <Button
                                        label="例文を追加"
                                        color="primary"
                                        plain
                                        @click="例文を追加"
                                    />
                                </div>
                                <template
                                    v-if="data.追加用の意味.examples.length > 0"
                                    v-for="例文 of data.追加用の意味.examples"
                                >
                                    <Textarea
                                        v-model="例文.sentence"
                                        label="例文 - 表"
                                    />
                                    <Textarea
                                        v-model="例文.meaning"
                                        label="例文 - 裏"
                                    />
                                </template>
                                <div class="content__button">
                                    <Button
                                        label="キャンセル"
                                        plain
                                        @click="意味の追加をキャンセル"
                                    />
                                    <Button
                                        label="追加"
                                        color="primary"
                                        plain
                                        :disabled="data.追加用の意味.wordMeaning === ''"
                                        @click="意味を確定"
                                    />
                                </div>
                            </Stack>
                        </div>
                    </template>
                    <div class="content__button--bottom">
                        <Button
                            label="戻る"
                            @click="一覧画面に戻る"
                        />
                        <Button
                            label="更新"
                            color="primary"
                            plain
                            :disabled="data.単語.name === '' || data.単語.meanings.length === 0"
                            @click="単語を更新"
                        />
                    </div>
                </Stack>
            </Center>
        </template>
    </OneColumnTemplate>
    <PopupModal
        :isActive="data.意味の削除モーダルフラグ"
        rightColor="danger"
        rightLabel="削除"
        @success="意味を削除する"
        @close="data.意味の削除モーダルフラグ=false"
    >
        <Stack space="0.5rem">
            <div>この意味を削除してよろしいですか？</div>
            <div class="modal__explanation">※一度削除すると削除前に戻すことはできません。</div>
        </Stack>
    </PopupModal>
    <PopupModal
        :isActive="data.例文の削除モーダルフラグ"
        rightColor="danger"
        rightLabel="削除"
        @success="例文を削除する"
        @close="data.例文の削除モーダルフラグ=false"
    >
        <Stack space="0.5rem">
            <div>この例文を削除してよろしいですか？</div>
            <div class="modal__explanation">※一度削除すると削除前に戻すことはできません。</div>
        </Stack>
    </PopupModal>
</template>

<style>
.content__button {
    display: flex;
    justify-content: end;
}

.content__button--bottom {
    display: flex;
    justify-content: center;
}

.content__form {
    font-size: small;
    color: gray;
}

.content__list {
    border: 1px solid #c9c9c9;
    border-radius: 0.5rem;
    padding: 0.5rem;
}

.meaning {
    display: flex;
    justify-content: space-between;
    justify-items: center;
}

.meaning__background {
    background-color: #F2F2F2;
    padding: 1rem;
    border-radius: 0.5rem;
}

.button__delete {
    cursor: pointer;
}

.modal__explanation {
    font-size: small;
}
</style>