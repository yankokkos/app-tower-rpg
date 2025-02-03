// src/services/api.js
import axios from 'axios';

const API_URL = 'http://srv130.prodns.com.br/api.php'; // URL da sua API

export const fetchFichas = async () => {
    const response = await axios.get(API_URL);
    return response.data;
};