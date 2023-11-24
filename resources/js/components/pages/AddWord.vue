<script setup lang="ts">
import { reactive } from 'vue';
import { uuid } from 'vue-uuid'
import { 例文型, 単語型, 意味型 } from '../../../../resources/js/types/word'
import { useRouter } from 'vue-router';
import axios from 'axios';
import Button from '@/components/atom/Button.vue';
import Input from '@/components/atom/Input.vue';
import Stack from '@/components/layout/Stack.vue'
import Header from '@/components/molecules/Header.vue';
import Textarea from '@/components/atom/Textarea.vue';
import Center from '@/components/layout/Center.vue'
import OneColumnTemplate from '@/components/templates/OneColumnTemplate.vue';
import Cluster from '@/components/layout/Cluster.vue';
import PopupModal from '@/components/molecules/PopupModal.vue'

const router = useRouter()

const data: {
    単語: 単語型
    意味: 意味型
    例文: 例文型
    意味の削除モーダルフラグ: boolean
    削除する意味id: string
    編集対象の意味id: string
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
    例文: {
        id: '',
        sentence: '',
        meaning: '',
        meaning_id: ''
    },
    意味の削除モーダルフラグ: false,
    削除する意味id: '',
    編集対象の意味id: ''
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

const 意味の削除モーダルを表示 = (意味id: string) => {
    data.削除する意味id = 意味id
    data.意味の削除モーダルフラグ = true
}

const 意味フォームを追加 = () => {
    data.意味 = {
        id: uuid.v1(),
        wordMeaning: '',
        examples: [],
        vocabulary_id: ''
    }
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

const 意味を追加 = () => {
    data.意味.examples = data.意味.examples.filter(x => !(x.sentence === '' || x.meaning === ''))
    data.単語.meanings.push(data.意味)
    data.意味 = {
        id: '',
        wordMeaning: '',
        examples: [],
        vocabulary_id: ''
    }
}

const 例文を追加 = () => {
    data.意味.examples.push(data.例文)
    data.例文 = {
        id: '',
        sentence: '',
        meaning: '',
        meaning_id: ''
    }
}

const 単語を登録 = async() => {
    await axios.post('/api/word/register', {
        name: data.単語.name,
        account_id: 7,
        meanings: data.単語.meanings
    }).then(() => {
        router.push('/list')
    }).catch((e) => {
        console.log(e);
    })
}

const 意味を削除する = () => {
    data.単語.meanings = data.単語.meanings.filter(x => x.id !== data.削除する意味id)
}

const キャンセル = () => {
    初期化処理()
}

const 一覧画面に戻る = () => {
    router.push('/list')
}

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
                            @click="意味フォームを追加"
                        />
                    </div>
                    <template
                        v-if="data.単語.meanings.length > 0"
                        v-for="meaning, index of data.単語.meanings"
                    >
                        <div class="content__form">
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
                                        <Stack space="0.3rem">
                                            <div>{{ `例文 - ${index + 1}` }}</div>
                                            <div>{{ 例文.sentence }}</div>
                                            <div>{{ 例文.meaning }}</div>
                                        </Stack>
                                    </div>
                                </template>
                            </Stack>
                        </div>
                    </template>
                    <template v-if="data.意味.id !== ''">
                        <div class="content__inner">
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
                                        @click="意味を追加"
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
                            label="登録"
                            color="primary"
                            plain
                            :disabled="data.単語.name === '' || data.単語.meanings.length === 0"
                            @click="単語を登録"
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
</template>

<style>
.content__inner {
    background-color: #F2F2F2;
    padding: 1rem;
    border-radius: 0.5rem;
}

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
</style>