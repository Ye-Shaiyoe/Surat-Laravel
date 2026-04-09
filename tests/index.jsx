import React, { useState } from 'react';

const Magic8Ball = () => {
  const [question, setQuestion] = useState('');
  const [answer, setAnswer] = useState('');
  const [isShaking, setIsShaking] = useState(false);

  const answers = [
    "It is certain.",
    "It is decidedly so.",
    "Without a doubt.",
    "Yes - definitely.",
    "You may rely on it.",
    "As I see it, yes.",
    "Most likely.",
    "Outlook good.",
    "Yes.",
    "Signs point to yes.",
    "Reply hazy, try again.",
    "Ask again later.",
    "Better not tell you now.",
    "Cannot predict now.",
    "Concentrate and ask again.",
    "Don't count on it.",
    "My reply is no.",
    "My sources say no.",
    "Outlook not so good.",
    "Very doubtful."
  ];

  const askQuestion = () => {
    if (!question.trim()) return;
    
    setIsShaking(true);
    setAnswer('');
    
    setTimeout(() => {
      const randomIndex = Math.floor(Math.random() * answers.length);
      setAnswer(answers[randomIndex]);
      setIsShaking(false);
    }, 1000);
  };

  return (
    <div style={styles.container}>
      <h1 style={styles.title}>Mystic 8-Ball</h1>
      
      <div style={styles.inputContainer}>
        <input 
          type="text" 
          value={question}
          onChange={(e) => setQuestion(e.target.value)}
          placeholder="Tanya sesuatu (Yes/No)..."
          style={styles.input}
        />
        <button onClick={askQuestion} style={styles.button} disabled={isShaking}>
          Kocok!
        </button>
      </div>

      <div style={{...styles.ball, ...(isShaking ? styles.shaking : {})}}>
        <div style={styles.window}>
          <div style={styles.answer}>
            {answer || "8"}
          </div>
        </div>
      </div>
      
      <style>
        {`
          @keyframes shake {
            0% { transform: translate(1px, 1px) rotate(0deg); }
            10% { transform: translate(-1px, -2px) rotate(-1deg); }
            20% { transform: translate(-3px, 0px) rotate(1deg); }
            30% { transform: translate(3px, 2px) rotate(0deg); }
            40% { transform: translate(1px, -1px) rotate(1deg); }
            50% { transform: translate(-1px, 2px) rotate(-1deg); }
            60% { transform: translate(-3px, 1px) rotate(0deg); }
            70% { transform: translate(3px, 1px) rotate(-1deg); }
            80% { transform: translate(-1px, -1px) rotate(1deg); }
            90% { transform: translate(1px, 2px) rotate(0deg); }
            100% { transform: translate(1px, -2px) rotate(-1deg); }
          }
        `}
      </style>
    </div>
  );
};

const styles = {
  container: {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    height: '100vh',
    backgroundColor: '#282c34',
    fontFamily: 'sans-serif',
    color: 'white',
  },
  title: {
    marginBottom: '20px',
    color: '#61dafb'
  },
  inputContainer: {
    marginBottom: '40px',
    display: 'flex',
    gap: '10px'
  },
  input: {
    padding: '10px',
    fontSize: '16px',
    borderRadius: '5px',
    border: 'none',
    width: '250px'
  },
  button: {
    padding: '10px 20px',
    fontSize: '16px',
    borderRadius: '5px',
    border: 'none',
    backgroundColor: '#61dafb',
    color: '#282c34',
    cursor: 'pointer',
    fontWeight: 'bold'
  },
  ball: {
    width: '300px',
    height: '300px',
    backgroundColor: '#111',
    borderRadius: '50%',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    boxShadow: 'inset -20px -20px 40px rgba(0,0,0,0.8), inset 10px 10px 30px rgba(255,255,255,0.2)',
    position: 'relative'
  },
  window: {
    width: '120px',
    height: '120px',
    backgroundColor: '#0a0a0a',
    borderRadius: '50%',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    border: '4px solid #222',
    boxShadow: 'inset 0 0 20px rgba(0,0,0,1)'
  },
  answer: {
    color: '#3498db',
    textAlign: 'center',
    padding: '10px',
    fontSize: '14px',
    textTransform: 'uppercase',
    fontWeight: 'bold',
    textShadow: '0 0 5px rgba(52, 152, 219, 0.5)'
  },
  shaking: {
    animation: 'shake 0.5s',
    animationIterationCount: '2'
  }
};

export default Magic8Ball;
