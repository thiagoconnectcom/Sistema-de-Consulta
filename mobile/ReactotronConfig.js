import Reactotron from "reactotron-react-native";

Reactotron.configure({ host: "192.168.15.24" })
  .useReactNative()
  .connect();
