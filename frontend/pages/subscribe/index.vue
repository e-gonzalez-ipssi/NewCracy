<template>
  <div>
    <Nav />
    <div class="container">
      <section>
        <main id="main" v-for="post in posts" :key="post">
          <div class="topBox">
            <div class="blockOne">
              <img src="https://via.placeholder.com/150" />
            </div>
            <div class="blockTwo">
              <div class="left">
                <h3>Neocracy</h3>
                <small>Social Media</small>
              </div>
              <div class="right">
                <button type="submit"></button>
              </div>
            </div>
          </div>
        </main>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      reveleFormPost: false,
      posts: [1, 2, 3, 4, 5, 6, 7, 8, 9],
    }
  },
}
</script>

<style scoped>
.container {
  margin: 0 auto;
  z-index: 1;
  display: flex;
  justify-content: center;
  padding-top: 120px;
  font-family: 'Open Sans', sans-serif;
}

a {
  text-decoration: none;
}

section {
  display: flex;
  align-items: center;
  flex-direction: column;
  margin: 0 auto;
  width: 100%;
}

#main {
  width: 30%;
  margin-bottom: 20px;
  box-sizing: border-box;
  border-radius: 10px;
  background-color: rgba(0, 0, 0, 0.01);
  background-image: linear-gradient(315deg, rgba(0, 0, 0, 0.01) 30%, #fff 100%);
  box-shadow: 0px 1px 10px 0px rgba(144, 144, 144, 0.5);
  -moz-box-shadow: 0px 1px 10px 0px rgba(144, 144, 144, 0.5);
  -webkit-box-shadow: 0px 1px 10px 0px rgba(144, 144, 144, 0.5);
}

.topBox {
  display: flex;
  flex-direction: column;
  padding: 20px 30px;
}

.topBox .blockOne {
  display: flex;
  justify-content: center;
}

.blockOne img {
  width: 100px;
  border: 3px solid #00d699;
  border-radius: 9999px;
}

.topBox .blockTwo {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
}

.left h3 {
  font-size: 25px;
  font-weight: bold;
  color: #424347;
}

.left small {
  font-size: 15px;
  color: #424347;
}

.right button {
  height: 50px;
  width: 150px;
  color: #fff;
  font-size: 16px;
  font-weight: bold;
  border-radius: 20px;
  border-style: none;
  transition: 0.2s ease;
  background-color: #00d699;
  background: linear-gradient(317deg, #00d699 30%, #00eeaa 100%);
}

.right button:hover {
  background: #e62117;
  border: 2px solid #e62117;
}

.right button i {
  padding-top: 5px;
}
</style>
