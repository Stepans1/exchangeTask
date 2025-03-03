import axios from 'axios';

const API_BASE_URL = 'http://127.0.0.1:8000/api';

export async function fetchCurrencies() {
  try {
    const response = await axios.get(`${API_BASE_URL}/availabel-currencies`);
    
    return response.data;
  } catch (error) {
    return [];
  }
}

export async function fetchRates(fromCurrency, toCurrency, selectedPage) {
  try {
    const response = await axios.get(`${API_BASE_URL}/exchange-rates`, {
      params: {
        base: fromCurrency,
        target: toCurrency,
        selectedPage : selectedPage,
      }
    });

    return response.data;
  } catch (error) {
    return null;
  }
}

export async function fetchLastUpdateTime() {
  try {
    const response = await axios.get(`${API_BASE_URL}/get-last-cron-update`, {
      params: {
        name : 'exchange:update',
      }
    });

    return response.data;
  } catch (error) {
    return [];
  }
}
