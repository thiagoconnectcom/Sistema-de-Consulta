import React, { Component } from "react";
import { StyleSheet, Text, View, FlatList } from "react-native";
import Header from "../../components/Header";
import api from "../../services/api";

export default class Home extends Component {
  state = {
    users: []
  };

  async componentDidMount() {
    try {
      const response = await api.get("/notification");
      this.setState({
        users: response.data.data
      });
      console.log(response);
    } catch (err) {
      console.log("Erro:", err);
    }
  }
  render() {
    return (
      <View>
        <Header />

        <View style={styles.container}>
          <Text style={styles.h2text}>Notificações</Text>
          <FlatList
            data={this.state.users}
            keyExtractor={item => item.id}
            renderItem={({ item }) => (
              <View style={styles.flatview}>
                <Text style={styles.name}>{item.message}</Text>
              </View>
            )}
          />
        </View>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    marginTop: 15,
    marginLeft: 15
  },
  h2text: {
    marginTop: 10,
    fontSize: 18,
    fontWeight: "bold"
  },
  flatview: {
    padding: 20,
    borderRadius: 2
  },
  name: {
    fontFamily: "Verdana",
    fontSize: 18,
    color: "#000"
  },
  email: {
    color: "red"
  }
});
