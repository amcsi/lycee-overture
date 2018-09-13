<template>
    <el-card class="card-list-item">
        <div class="card-list-item-inner">
            <CardThumbnail class="card-thumbnail" :id="card.id" />
            <div class="card-details">
                <div class="names-and-type">
                    <span class="card-id">{{ card.id }}</span>
                    <span class="card-name">{{ card.translation.name }}</span>
                    <span v-if="isCharacter">
                        - <span class="card-ability-name">{{ card.translation.ability_name }}</span>
                        <span class="card-character-type" v-if="characterType">- Type: {{ card.translation.character_type || '-' }}</span>
                    </span>
                </div>
                <div class="stats-and-stuff">
                    <span class="stat ex">{{ card.ex }} EX</span>
                    <template v-if="isCharacter">
                        <span class="stat dmg">{{ card.dmg }} DMG</span>
                        <span class="stat ap">{{ card.ap }} AP</span>
                        <span class="stat dp">{{ card.dp }} DP</span>
                        <span class="stat sp">{{ card.sp }} SP</span>
                    </template>
                </div>
                <div class="card-description" v-if="hasCardDescription">
                    <CardDescription :translation="card.translation" />
                </div>
            </div>
        </div>
    </el-card>
</template>

<script>
  import CardDescription from './CardDescription';
  import CardThumbnail from './CardThumbnail';

  /** @class CardListItem */
  export default {
    name: 'CardListItem',
    components: { CardDescription, CardThumbnail },
    props: {
      card: {
        type: Object,
        required: true,
      },
    },
    computed: {
      isCharacter() {
        return this.card.type === 0;
      },
      characterType() {
        if (!this.isCharacter) {
          return '';
        }
        const characterType = this.card.translation.character_type.trim();
        if (characterType.length <= 1) {
          // Empty or just a dash; return empty string.
          return '';
        }
        return characterType;
      },
      hasCardDescription() {
        const translation = this.card.translation;
        return translation.ability_cost.trim().length > 1 || translation.ability_description.trim().length > 1 || translation.comments.trim().length > 1;
      },
    },
  };
</script>

<style scoped>
    .card-list-item {
        margin-bottom: .5rem;
    }

    .card-list-item-inner {
        display: flex;
        flex-direction: row;
    }

    .card-thumbnail {
        margin-left: -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .card-details {
        flex: 1;
    }

    .names-and-type {
        margin-bottom: .5rem;
        color: gray;
    }

    .card-id {
        font-weight: bold;
        font-size: 1.2em;
        color: #555555;
    }

    .stats-and-stuff {
        margin-bottom: .5rem;
    }

    .stat {
        color: white;
        padding: .3rem .4rem;
        font-size: .75rem;
        line-height: 1.2rem;
        border-radius: 1rem;
    }

    .ex {
        background-color: #333333;
    }

    .dmg {
        background-color: rgba(0, 128, 0, 1);
    }

    .ap {
        background-color: rgba(255, 0, 0, 1);
    }

    .dp {
        background-color: rgba(0, 0, 255, 1);
    }

    .sp {
        background-color: rgb(255, 146, 35);
    }

    .card-description {
        border-top: 1px dashed #a4a4a4;
        padding-top: .5rem;
    }
</style>