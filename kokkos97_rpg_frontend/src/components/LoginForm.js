// src/components/LoginForm.js
import React, { useState } from 'react';
import axios from 'axios';

const LoginForm = () => {
    const [nome, setNome] = useState('');
    const [senha, setSenha] = useState('');
    const [error, setError] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post('http://srv130.prodns.com.br/login.php', { nome, senha });
            // Armazenar informações do usuário na sessão ou no estado global
            console.log(response.data);
            // Redirecionar para o dashboard ou outra página
        } catch (err) {
            setError('Nome de usuário ou senha inválidos.');
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <h2>Login</h2>
            {error && <p className="error">{error}</p>}
            <input
                type="text"
                placeholder="Nome de Usuário"
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
            <button type="submit">Entrar</button>
        </form>
    );
};

export default LoginForm;