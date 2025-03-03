<template>
    <div class="container">
      <h1 class="title">Currency Exchange Rate</h1>
      <p class="subtitle">Last updated: {{ lastUpdate }}</p>
  
      <div class="currency-selector">
        <select v-model="selectedCurrencyFrom">
          <option v-for="(currency, index) in currencies" :key="index" :value="currency">
            {{ currency }}
          </option>
        </select>
  
        <span class="currency-to">To</span>
  
        <select v-model="selectedCurrencyTo">
          <option v-for="(currency, index) in currencies" :key="index" :value="currency">
            {{ currency }}
          </option>
        </select>
      </div>

      <div v-if="totalPages > 0" class="pagination">
        &lt;
        <a 
          v-for="page in pages" 
          :key="page"
          @click="changePage(page)" 
          :class="{'active': selectedPage === page}"
        >
        {{ page }}
        </a> 
        &gt;
        </div>

      <div class="table-container">
        <table class="exchange-table">
          <thead>
            <tr>
              <th>Date ⬆</th>
              <th>{{ selectedCurrencyFrom }} to {{ selectedCurrencyTo }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(rate, index) in exchangeRates" :key="index">
              <td>{{ rate.formatted_created_at }}</td>
              <td>{{ rate.rate }}</td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <div v-if="totalPages > 0" class="pagination">
        &lt;
        <a 
          v-for="page in pages" 
          :key="page"
          @click="changePage(page)" 
          :class="{'active': selectedPage === page}"
        >
        {{ page }}
        </a> 
        &gt;
        </div>
  
      <p v-if="statistic" class="stats">
        Minimum: {{ statistic.min ? statistic.min  + " " + selectedCurrencyTo : ""}} , Maximum: {{ statistic.max ? statistic.max + " " + selectedCurrencyTo : ""}}<br>
        Average: {{ statistic.avg ? statistic.avg + " " + selectedCurrencyTo : ""}}
      </p>
    </div>
</template>
  
  <script>
  import { fetchCurrencies, fetchRates, fetchLastUpdateTime } from '../api/api.js';
  
  export default {
    data() {
      return {
        lastUpdate: null,
        currencies: [],  
        selectedCurrencyFrom: 'EUR',  
        selectedCurrencyTo: 'USD',
        selectedPage: 1,   
        totalPages: 0,
        exchangeRates: [],
        statistic: {min: null, max: null, avg: null},
      };
    },
  
    async mounted() {
      const currenciesData = await fetchCurrencies();
      if (currenciesData.isSuccess) {
        this.currencies = currenciesData.data;
      }

      this.fetchExchangeRate();  
      this.fetchLastUpdateTime();
    },
  
    watch: {
      selectedCurrencyFrom(newCurrency, oldCurrency) {
        if (newCurrency !== oldCurrency) {
          this.selectedPage = 1;
          this.fetchExchangeRate();
        }
      },
      selectedCurrencyTo(newCurrency, oldCurrency) {
        if (newCurrency !== oldCurrency) {
          this.selectedPage = 1;
          this.fetchExchangeRate();
        }
      },
      selectedPage(newPage, oldPage) {
        if (newPage !== oldPage) {
          this.fetchExchangeRate();
        }
      },
    },

    computed: {
      pages() {
        return Array.from({ length: this.totalPages }, (v, k) => k + 1);
      }
    },

    methods: {
      async fetchExchangeRate() {
          const response = await fetchRates(this.selectedCurrencyFrom, this.selectedCurrencyTo, this.selectedPage);
          if (response.isSuccess) {
            this.exchangeRates = response.data.rates;
            this.statistic = response.data.statistic;
            this.totalPages = response.data.pagination.lastPage;
          } else {
            this.exchangeRates = []
            this.statistic = null;
            this.totalPages = 0;
          }
      },

      async fetchLastUpdateTime() {
        const response = await fetchLastUpdateTime();

        if (response.isSuccess) {
           this.lastUpdate = response.data.lastUpdate;
        }
      },

      changePage(newPage) {
        this.selectedPage = newPage;
      }
    },
  };
  </script>
  
  <style>
  .container {
    max-width: 600px;
    margin: auto;
    padding: 20px;
  }
  .title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
  }
  .subtitle {
    text-align: center;
    color: gray;
  }
  .currency-selector {
    display: flex;
    justify-content: center;
    align-items: center; 
    gap: 10px;
    margin-top: 20px;
  }
  .currency-selector select {
    padding: 5px;
    font-size: 14px;
  }
  .currency-to {
    font-size: 16px; 
    font-weight: bold;
    margin: 0 10px;
  }
  .table-container {
    overflow-x: auto;
    margin-top: 20px;
  }
  .exchange-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid gray;
    text-align: center;
  }
  .exchange-table th, .exchange-table td {
    border: 1px solid gray;
    padding: 8px;
  }
  .exchange-table th {
    background: #f0f0f0;
  }
  .pagination {
  text-align: center; 
  margin-top: 20px;
  color: gray;
  display: flex;
  gap: 5px; 
  justify-content: center;
}
.pagination a {
  cursor: pointer;
}
.stats {
    text-align: center;
    color: gray;
    margin-top: 20px;
  }
.pagination a.active {
    font-weight: bold;
    color: red; 
}
</style>
  