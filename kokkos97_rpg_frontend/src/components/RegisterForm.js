// src/components/RegisterForm.js
import React, { useState } from 'react';
import axios from 'axios';

const RegisterForm = () => {
    const [nome, setNome] = useState('');
    const [senha, setSenha] = useState('');
    const [error, setError] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post('http://srv130.prodns.com.br/register.php', { nome, senha });
            // Redirecionar para a página de login ou dashboard
        } catch (err) {
            setError('Erro ao registrar. Tente novamente.');
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <h2>Registrar</h2>
            {error && <p className="error">{error}</p>}
            <input
                type="text"
                placeholder="Nome"
                value={nome}
                onChange={(e) => setNome(e.target.value)}
                required
            />
            <input
                type="password"
                placeholder="Senha"
                value={senha}
                onChange={(e) => setSenha(e.target.value)}
                required
            />
            <button type="submit">Registrar</button>
        </form>
    );
};

export default RegisterForm;