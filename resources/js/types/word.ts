export interface 例文型 {
    id: string
    sentence: string
    meaning: string
    meaning_id: string
}

export interface 意味型 {
    id: string
    wordMeaning: string
    vocabulary_id: string
    examples: 例文型[]
}


export interface 単語型 {
    id: string
    name: string
    meanings: 意味型[]
}