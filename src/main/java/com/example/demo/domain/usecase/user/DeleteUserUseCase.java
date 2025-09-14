package com.example.demo.domain.usecase.user;

import com.example.demo.domain.entities.user.IUser;
import com.example.demo.domain.entities.user.IUserRepository;
import com.example.demo.domain.entities.user.User;
import com.example.demo.domain.usecase.user.dto.IUserRegistrationData;

public class DeleteUserUseCase {
    private final IUserRepository userRepository;

    public DeleteUserUseCase(IUserRepository userRepository) {
        this.userRepository = userRepository;
    }

    public void execute(Long id) {
        User user = userRepository.findById(id);
        if (user == null) {
            throw new IllegalArgumentException("Usuário com id " + id + " não encontrado.");
        }

        userRepository.delete(id);
    }
}
