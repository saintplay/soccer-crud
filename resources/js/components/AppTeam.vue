<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-layout justify-space-between wrap>
          <span class="title">Jugadores</span>

          <div>
            <v-select
              v-model="playersByPositionToView"
              :items="playersByPositions"
              label="Filtrar por Posición"
              :hint="`Se están mostrando ${actualPlayers.length} jugadores`"
              persistent-hint
            />
          </div>

          <div>
            <v-text-field
              ref="searchField"
              v-model.trim="nameToSearch"
              label="Buscar Jugador"
              :append-icon="nameToSearch ? 'mdi-close' : 'mdi-magnify'"
              @click:append="() => onCancelSearch()"
            />
          </div>

          <v-btn color="primary" dark @click.native="() => startCreatingPlayer()">
            <v-icon light>mdi-plus</v-icon>
            <span class="pl-2">Nuevo</span>
          </v-btn>
        </v-layout>
      </v-col>

      <v-col cols="12">
        <v-data-table
          :headers="[
            { text: 'Nombre', value: 'name', align: 'center', sortable: false },
            { text: 'Posición', value: 'position',  align: 'center', sortable: false },
            { text: 'Goles', value: 'goals',  align: 'center', sortable: false },
            { text: 'Acciones', value: 'actions', align: 'center', sortable: false }
          ]"
          :items="actualPlayers"
          no-data-text="No se encontraron jugadores"
          item-key="id"
          hide-default-footer
        >
          <template slot="item.goals" slot-scope="{ item: player }">
            <app-goals v-model="player.goals" />
          </template>
          <template slot="item.actions" slot-scope="{ item: player }">
            <v-icon small class="mr-2" @click="() => startEditingPlayer(player)">mdi-pencil</v-icon>
            <v-icon small @click="() => startDeletingPlayer(player)">mdi-delete</v-icon>
          </template>
        </v-data-table>
      </v-col>
    </v-row>

    <app-alert
      v-model="deleteDialog"
      title="¿Quieres eliminar al jugador?"
      :subtitle="selectedPlayer ? selectedPlayer.name : null"
      ok-text="Sí, Eliminar"
      @cancel="deleteDialog = false"
      @confirm="() => onDeletePlayer()"
    />

    <app-alert
      v-model="createOrEditDialog"
      :title="editingExistingPlayer ? 'Actualizar datos' : 'Nuevo Jugador'"
      ok-text="Guardar"
      @cancel="createOrEditDialog = false"
      @confirm="() => onCreateOrEditPlayer()"
    >
      <v-card-text v-if="selectedPlayer">
        <v-container>
          <v-row>
            <v-col cols="6" sm="12">
              <v-text-field v-model="selectedPlayer.name" label="Name"></v-text-field>
            </v-col>
            <v-col cols="6" sm="12">
              <v-select
                v-model="selectedPlayer.position"
                :items="PLAYERS_BY_POSITIONS"
                label="Posición"
              />
            </v-col>
            <v-col cols="6" sm="12">
              <v-text-field v-model.number="selectedPlayer.goals" type="number" label="Goles"></v-text-field>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
    </app-alert>
  </v-container>
</template>

<script>
import players from "../constants/players";

import AppGoals from "./AppGoals";

const getDefaultPlayer = () => ({
  name: "",
  position: "",
  goals: 0
});

const PLAYERS_BY_POSITIONS_ALL = "Todos";
const PLAYERS_BY_POSITIONS = ["Defensa", "Centrocampista", "Delantero"];

export default {
  components: {
    AppGoals
  },
  data: () => ({
    players,
    createOrEditDialog: false,
    editingExistingPlayer: false,
    deleteDialog: false,
    selectedPlayer: null,
    playersByPositionToView: PLAYERS_BY_POSITIONS_ALL,
    playersByPositions: [...PLAYERS_BY_POSITIONS, PLAYERS_BY_POSITIONS_ALL],
    nameToSearch: "",
    PLAYERS_BY_POSITIONS
  }),
  computed: {
    selectedPlayerIndex() {
      if (!this.selectedPlayer || !this.selectedPlayer.id) return -1;
      return this.players.findIndex(p => p.id === this.selectedPlayer.id);
    },
    filteredByPosition() {
      if (this.playersByPositionToView === PLAYERS_BY_POSITIONS_ALL)
        return this.players;
      return this.players.filter(
        p => p.position === this.playersByPositionToView
      );
    },
    actualPlayers() {
      if (!this.nameToSearch) return this.filteredByPosition;
      const regex = new RegExp(this.nameToSearch, "i");
      return this.filteredByPosition.filter(p => regex.test(p.name));
    }
  },
  methods: {
    startCreatingPlayer(player) {
      // For nested objects use lodash.get instead
      this.selectedPlayer = getDefaultPlayer();
      this.createOrEditDialog = true;
      this.editingExistingPlayer = false;
    },
    startEditingPlayer(player) {
      // For nested objects use lodash.get instead
      this.selectedPlayer = { ...player };
      this.createOrEditDialog = true;
      this.editingExistingPlayer = true;
    },
    startDeletingPlayer(player) {
      this.selectedPlayer = player;
      this.deleteDialog = true;
    },
    onDeletePlayer() {
      this.deleteDialog = false;

      if (!this.selectedPlayer) return;

      this.players = this.players.filter(p => p.id !== this.selectedPlayer.id);
      this.selectedPlayer = null;
    },
    onCreateOrEditPlayer() {
      this.createOrEditDialog = false;

      if (!this.selectedPlayer) return;

      if (this.editingExistingPlayer) {
        if (this.selectedPlayerIndex === -1) return;
        this.players[this.selectedPlayerIndex] = { ...this.selectedPlayer };
      } else {
        // Fake id for now, since we are not hitting the database
        const id = Date.now();
        this.players.unshift({ ...this.selectedPlayer, id });
      }

      this.selectedPlayer = null;
      this.editingExistingPlayer = false;
      // Cancel filtering in order to view the new player or edited player
      this.playersByPositionToView = PLAYERS_BY_POSITIONS_ALL;
      this.onCancelSearch();
    },
    onCancelSearch() {
      this.nameToSearch = "";

      const { searchField: searchFieldRef } = this.$refs;
      if (searchFieldRef && searchFieldRef.blur) {
        searchFieldRef.blur();
      }
    }
  }
};
</script>
