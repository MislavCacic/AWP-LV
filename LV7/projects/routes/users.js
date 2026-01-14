var express = require('express');
var router = express.Router();
var bcrypt = require('bcryptjs');

// In-memory storage for users (in production, use a database)
let users = [];

// Export users array so it can be accessed from other routes
router.getUsers = function() {
  return users;
};

/* GET registration form */
router.get('/register', function(req, res, next) {
  res.render('users/register', { 
    title: 'Registracija',
    error: null
  });
});

/* POST registration */
router.post('/register', async function(req, res, next) {
  const { username, email, password, password2 } = req.body;
  
  // Validation
  if (!username || !email || !password || !password2) {
    return res.render('users/register', {
      title: 'Registracija',
      error: 'Sva polja su obavezna'
    });
  }
  
  if (password !== password2) {
    return res.render('users/register', {
      title: 'Registracija',
      error: 'Lozinke se ne podudaraju'
    });
  }
  
  // Check if user already exists
  const userExists = users.find(u => u.username === username || u.email === email);
  if (userExists) {
    return res.render('users/register', {
      title: 'Registracija',
      error: 'Korisničko ime ili email već postoji'
    });
  }
  
  // Hash password
  const hashedPassword = await bcrypt.hash(password, 10);
  
  // Create new user
  const newUser = {
    id: users.length + 1,
    username: username,
    email: email,
    password: hashedPassword
  };
  
  users.push(newUser);
  
  // Redirect to login
  res.redirect('/users/login');
});

/* GET login form */
router.get('/login', function(req, res, next) {
  res.render('users/login', { 
    title: 'Prijava',
    error: null
  });
});

/* POST login */
router.post('/login', async function(req, res, next) {
  const { username, password } = req.body;
  
  // Validation
  if (!username || !password) {
    return res.render('users/login', {
      title: 'Prijava',
      error: 'Sva polja su obavezna'
    });
  }
  
  // Find user
  const user = users.find(u => u.username === username);
  if (!user) {
    return res.render('users/login', {
      title: 'Prijava',
      error: 'Neispravno korisničko ime ili lozinka'
    });
  }
  
  // Check password
  const isMatch = await bcrypt.compare(password, user.password);
  if (!isMatch) {
    return res.render('users/login', {
      title: 'Prijava',
      error: 'Neispravno korisničko ime ili lozinka'
    });
  }
  
  // Set session
  req.session.user = {
    id: user.id,
    username: user.username,
    email: user.email
  };
  
  res.redirect('/projects');
});

/* GET logout */
router.get('/logout', function(req, res, next) {
  req.session.destroy(function(err) {
    if (err) {
      return next(err);
    }
    res.redirect('/users/login');
  });
});

module.exports = router;
