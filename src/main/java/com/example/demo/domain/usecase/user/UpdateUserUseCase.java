package com.example.demo.domain.usecase.user;

import com.example.demo.domain.entities.user.IUser;
import com.example.demo.domain.entities.user.IUserRepository;
import com.example.demo.domain.entities.user.User;
import com.example.demo.domain.usecase.user.dto.IUserRegistrationData;

public class UpdateUserUseCase {
    private final IUserRepository userRepository;

    public UpdateUserUseCase(IUserRepository userRepository) {
        this.userRepository = userRepository;
    }

    public User execute(Long userId, IUserRegistrationData data) {
        User existingUser = userRepository.findById(userId)
            .orElseThrow(() -> new NoSuchElementException("User not found"));

        existingUser.setUsername(data.getUsername());
        existingUser.setEmail(data.getEmail());
        existingUser.setPassword(data.getPassword());
        existingUser.setRole(data.getRole());

        return userRepository.save(existingUser);
    }
}
