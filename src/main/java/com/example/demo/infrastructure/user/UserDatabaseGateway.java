package com.example.demo.infrastructure.user;

import com.example.demo.data.schema.UserSchema;
import com.example.demo.domain.entities.user.IUser;
import com.example.demo.domain.entities.user.IUserRepository;
import com.example.demo.domain.entities.user.User;

import java.util.List;
import java.util.Optional;

import com.example.demo.data.repositories.*;

public class UserDatabaseGateway implements IUserRepository {
    private final UserRepository userRepository;
    
    public UserDatabaseGateway(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    @Override
    public User save(User user) {
        UserSchema userSchema = new UserSchema(user);
        return userRepository.save(userSchema).toUser();
    }

    @Override
    public boolean delete(Long id) {
        if (!userRepository.existsById(id)) {
            return false;
        }
        userRepository.deleteById(id);
        return true;
    }

    @Override
    public Optional<User> findById(Long id) {
        return userRepository.findById(id)
                .map(user -> user.toUser()); // Safe cast
    }

    @Override
    public List<User> searchByName(String nameFragment) {
        return userRepository.findByUsernameContaining(nameFragment)
                .stream()
                .map(UserSchema::toUser) // Safe cast
                .toList();
    }

    @Override
    public List<User> searchByEmail(String emailFragment){
        return userRepository.findByEmailContaining(emailFragment)
                .stream()
                .map(UserSchema::toUser) // Safe cast
                .toList();
    }
}
